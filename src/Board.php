<?php

namespace App;

use App\Contract\Renderable;
use App\Enum\PieceColor;
use App\Enum\PieceType;
use App\Piece\Piece;

class Board implements Renderable
{
        
    private array $pieces = [];

    // Permet de placer la pièce AU DEBUT du jeu
    public function placePiece(Piece $piece): void 
    {
        $this->pieces[$piece->getPosition()->toKey()] = $piece;
    }

    public function getPieceAt(Position $position): ?Piece
    {
        return $this->pieces[$position->toKey()] ?? null;
    }

    public function hasPieceAt(Position $position): bool
    {
        return isset($this->pieces[$position->toKey()]);
    }

    public function removePieceAt(Position $position): void
    {
        unset($this->pieces[$position->toKey()]); 
    }

    // permet de déplacer les pièces PENDANT le jeu
    public function movePiece(Position $from, Position $to): void
    {
        $piece = $this->getPieceAt($from);
        $this->removePieceAt($from);
        $piece->setPosition($to);
        $this->placePiece($piece);
    }

    public function isPathClear(Position $from, Position $to): bool
    {
        $stepRow = ($to->getRow() <=> $from->getRow());
        $stepColumn = ($to->getColumn() <=> $from->getColumn());
        $row = $from->getRow() + $stepRow;
        $col = $from->getColumn() + $stepColumn;
        while ($row !== $to->getRow() || $col !== $to->getColumn()) {
            if ($this->hasPieceAt(new Position($row, $col))) {
                return false;
            }
            $row += $stepRow;
            $col += $stepColumn;
        }
        return true;
    }

    public function getPieces(): array
    {
        return array_values($this->pieces);
    }

    public function getKingPosition(PieceColor $color): ?Position
    {
        foreach ($this->pieces as $piece) {
            if ($piece->getType() === PieceType::KING && $piece->getColor() === $color) {
                return $piece->getPosition();
            }
        }
        return null;
    }

    public function render(): string
    {
        $output = "";
        for ($row = 0; $row < 8; $row++) {
            for ($col = 0; $col < 8; $col++) {
                $piece = $this->getPieceAt(new Position($row, $col));
                $output .= $piece !== null ? $piece->render() : ".";
            }   
            $output .= "\n";
        }   
        return $output;

    }
    
}
?>