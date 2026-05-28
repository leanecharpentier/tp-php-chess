<?php

namespace App;

use App\Enum\PieceColor;
use App\Enum\PieceType;
use App\Factory\PieceFactory;
use App\Exception\NoPieceException;
use App\Exception\WrongTurnException;
use App\Exception\InvalidMoveException;
use App\Exception\OccupiedByAllyException;

class Game
{
	private Board $board;
	private PieceColor $currentPlayer;
	private PieceFactory $pieceFactory;
	
	public function __construct()
	{
		$this->board = new Board();
		$this->currentPlayer = PieceColor::WHITE;
		$this->pieceFactory = new PieceFactory();
	}

	public function start(): void
	{
		$this->setupPieces();
	}

	public function getBoard(): Board
	{
		return $this->board;
	}

	public function getCurrentPlayer(): PieceColor
	{
		return $this->currentPlayer;
	}

	public function play(Move $move): void
	{
		$from = $move->getFrom();
		$to   = $move->getTo();

		$piece = $this->board->getPieceAt($from);
		if ($piece === null) {
			throw new NoPieceException("Aucune pièce sur la case de départ");
		}
	
		if ($piece->getColor() !== $this->currentPlayer) {
			throw new WrongTurnException("Ce n'est pas le tour de ce joueur");
		}

		$pieceAtTarget = $this->board->getPieceAt($to);
		if ($pieceAtTarget !== null && $pieceAtTarget->getColor() === $piece->getColor()) {
			throw new OccupiedByAllyException("La case cible est occupée par un allié");
		}

		if ($piece->canMove($this->board, $to) === false) {
			throw new InvalidMoveException("Déplacement interdit pour cette pièce");
		}

		$this->board->movePiece($from, $to);

		$this->switchPlayer();
	}


	public function isCheck(PieceColor $color): bool
	{
		$kingPosition = $this->board->getKingPosition($color);
		if ($kingPosition === null) {
			return false;
		}
	
		foreach ($this->board->getPieces() as $piece) {
			if ($piece->getColor() !== $color) {
				if ($piece->canMove($this->board, $kingPosition)) {
					return true;
				}
			} 
		}

		return false;
	}

	private function setupPieces(): void
	{
		$backRank = [
			PieceType::ROOK, PieceType::KNIGHT, PieceType::BISHOP, PieceType::QUEEN,
			PieceType::KING, PieceType::BISHOP, PieceType::KNIGHT, PieceType::ROOK,
		];
		
		for ($col = 0; $col < 8; $col++) {
			$pieceBlack = $this->pieceFactory->create($backRank[$col], PieceColor::BLACK, new Position(0, $col));
			$this->board->placePiece($pieceBlack);

			$pieceWhite = $this->pieceFactory->create($backRank[$col], PieceColor::WHITE, new Position(7, $col));
			$this->board->placePiece($pieceWhite);

			$pawnBlack = $this->pieceFactory->create(PieceType::PAWN, PieceColor::BLACK, new Position(1, $col));
			$this->board->placePiece($pawnBlack);

			$pawnWhite = $this->pieceFactory->create(PieceType::PAWN, PieceColor::WHITE, new Position(6, $col));
			$this->board->placePiece($pawnWhite);
		}   

	}

	private function switchPlayer(): void
	{
		$this->currentPlayer = $this->currentPlayer->opposite();
	}
}
