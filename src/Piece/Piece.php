<?php

namespace App\Piece;

use App\Board;
use App\Contract\Renderable;
use App\Enum\PieceColor;
use App\Enum\PieceType;
use App\Position;

abstract class Piece implements Renderable
{
    protected PieceColor $color;
    protected Position $position;
    protected PieceType $type;

    public function __construct(PieceColor $color, Position $position)
    {
        $this->color = $color;
        $this->position = $position;
    }

    public function getColor(): PieceColor
    {
        return $this->color;
    }

    public function getPosition(): Position
    {
        return $this->position;
    }

    public function setPosition(Position $position): void
    {
        $this->position = $position;
    }

    public function getType(): PieceType
    {
        return $this->type;
    }

    public function canMove(Board $board, Position $target): bool
    {
        if ($target->equals($this->position)) {
            return false;
        }

        if ($this->isValidMovementShape($target) === false) {
            return false;
        }

        $pieceAtTarget = $board->getPieceAt($target);

        if ($pieceAtTarget !== null && $pieceAtTarget->getColor() === $this->getColor()) {
            return false;
        }

        if ($this->getType() !== PieceType::KNIGHT) {
            if ($board->isPathClear($this->getPosition(), $target) === false) {
                return false;
            }
        }

        if ($this->getType() === PieceType::PAWN) {
            $sameColumn = $target->getColumn() === $this->position->getColumn();

            if ($sameColumn) {
                // Avance tout droit → la case d'arrivée doit être VIDE
                // (sinon le pion "mangerait" droit devant, ce qui est interdit)
                return $pieceAtTarget === null;
            }

            // Sinon c'est une diagonale → seule une capture d'ennemi est permise
            return $this->canCapture($board, $target);
        }

        return true;
    }

    protected function canCapture(Board $board, Position $target): bool
    {
        $pieceAtTarget = $board->getPieceAt($target);

        if ($pieceAtTarget !== null && $pieceAtTarget->getColor() !== $this->getColor()) {
            return true;
        }

        return false;
    }    

    abstract public function render(): string;

    abstract protected function isValidMovementShape(Position $target): bool;

}

?>