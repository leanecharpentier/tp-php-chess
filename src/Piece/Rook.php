<?php

namespace App\Piece;

use App\Enum\PieceColor;
use App\Enum\PieceType;
use App\Position;

class Rook extends Piece
{
    public function __construct(PieceColor $color, Position $position)
    {
        parent::__construct($color, $position);
        $this->type = PieceType::ROOK;
    }

    protected function isValidMovementShape(Position $target): bool
    {
        $gapRow = $this->position->getRow() - $target->getRow();
        $gapColumn = $this->position->getColumn() - $target->getColumn();

        if (($gapColumn === 0) !== ($gapRow === 0)) {
            return true;
        }


        return false;
    }

    public function render(): string
    {
        if ($this->getColor() === PieceColor::WHITE) {
            return "R";
        } else {
            return "r";
        }
    }
}