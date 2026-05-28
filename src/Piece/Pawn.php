<?php

namespace App\Piece;

use App\Board;
use App\Enum\PieceColor;
use App\Enum\PieceType;
use App\Position;

class Pawn extends Piece
{
    public function __construct(PieceColor $color, Position $position)
    {
        parent::__construct($color, $position);
        $this->type = PieceType::PAWN;
    }

    protected function isValidMovementShape(Position $target): bool
    {
        $gapRow = $target->getRow() - $this->position->getRow();
        $gapColumn = $target->getColumn() - $this->position->getColumn();

        if ($this->getColor() === PieceColor::WHITE && ($gapColumn === 0 && $gapRow === -1)) {
            return true;
        }

        if ($this->getColor() === PieceColor::BLACK && ($gapColumn === 0 && $gapRow === 1)) {
            return true;
        }

        // Avance de 2 cases au premier coup (blanc)
        if ($this->getColor() === PieceColor::WHITE && $this->position->getRow() === 6 && $gapRow === -2 && $gapColumn === 0) {
            return true;
        }

        // Avance de 2 cases au premier coup (noir)
        if ($this->getColor() === PieceColor::BLACK && $this->position->getRow() === 1 && $gapRow === 2 && $gapColumn === 0) {
            return true;
        }

        // Capture en diagonale (1 case - blanc)
        if ($this->getColor() === PieceColor::WHITE && (abs($gapColumn) === 1 && $gapRow === -1)) {
            return true;
        }

        // Capture en diagonale (1 case - noir)
        if ($this->getColor() === PieceColor::BLACK && (abs($gapColumn) === 1 && $gapRow === 1)) {
            return true;
        }

        return false;
    }

    protected function canCapture(Board $board, Position $target): bool
    {
        $pieceEnemiAtTarget = parent::canCapture($board, $target);

        if ($pieceEnemiAtTarget === false) {
            return false;
        }

        $gapRow = $target->getRow() - $this->position->getRow();
        $gapColumn = $target->getColumn() - $this->position->getColumn();

        if ($this->getColor() === PieceColor::WHITE && (abs($gapColumn) === 1 && $gapRow === -1)) {
            return true;
        }

        if ($this->getColor() === PieceColor::BLACK && (abs($gapColumn) === 1 && $gapRow === 1)) {
            return true;
        }

        return false;
    }

    public function render(): string
    {
        if ($this->getColor() === PieceColor::WHITE) {
            return "P";
        } else {
            return "p";
        }
    }
}