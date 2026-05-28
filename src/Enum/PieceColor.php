<?php

namespace App\Enum;

enum PieceColor: string
{
    case WHITE = "white";
    case BLACK = "black";

    public function opposite(): PieceColor
    {
        return match($this) {
            self::WHITE => self::BLACK,
            self::BLACK => self::WHITE,
        };
    }
}

?>