<?php

namespace App\Piece;

use App\Enum\PieceColor;
use App\Enum\PieceType;
use App\Position;

class Knight extends Piece
{
    public function __construct(PieceColor $color, Position $position)
    {
        parent::__construct($color, $position);
        $this->type = PieceType::KNIGHT;
    }

    protected function isValidMovementShape(Position $target): bool
    {
        $gapRow = $this->position->getRow() - $target->getRow();
        $gapColumn = $this->position->getColumn() - $target->getColumn();

        if ((abs($gapColumn) === 1 && abs($gapRow) === 2) || (abs($gapColumn) === 2 && abs($gapRow) === 1)) {
            return true;
        }

        return false;
    }

    public function render(): string
    {
        if ($this->getColor() === PieceColor::WHITE) {
            return "N";
        } else {
            return "n";
        }
    }
}