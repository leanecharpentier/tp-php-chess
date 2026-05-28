<?php

namespace App\Piece;

use App\Enum\PieceColor;
use App\Enum\PieceType;
use App\Position;

class Queen extends Piece
{
    public function __construct(PieceColor $color, Position $position)
    {
        parent::__construct($color, $position);
        $this->type = PieceType::QUEEN;
    }

    protected function isValidMovementShape(Position $target): bool
    {
        $gapRow = $this->position->getRow() - $target->getRow();
        $gapColumn = $this->position->getColumn() - $target->getColumn();

        if ((abs($gapColumn) === 0 || abs($gapRow) === 0) || (abs($gapColumn) === abs($gapRow))) {
            return true;
        }

        return false;
    }

    public function render(): string
    {
        if ($this->getColor() === PieceColor::WHITE) {
            return "Q";
        } else {
            return "q";
        }
    }
}