<?php

namespace App\Enum;

enum PieceType: string
{
    case PAWN = "pawn";
    case ROOK = "rook";
    case KNIGHT = "knight";
    case BISHOP = "bishop";
    case QUEEN = "queen";
    case KING = "king";
}

?>