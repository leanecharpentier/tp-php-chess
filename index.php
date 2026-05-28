<?php

require __DIR__ . '/vendor/autoload.php';

use App\Game;
use App\Move;
use App\Position;
use App\Exception\ChessException;

$game = new Game();
$game->start();

echo "Plateau initial :\n";
echo $game->getBoard()->render();

// quelques coups de démonstration
$moves = [
    new Move(new Position(6, 4), new Position(4, 4)), // e2-e4 (blanc)
    new Move(new Position(1, 4), new Position(3, 4)), // e7-e5 (noir)
    // ajoute-en 2-3 autres si tu veux
];

foreach ($moves as $move) {
    try {
        $game->play($move);
    } catch (ChessException $e) {
        echo "Coup refusé : " . $e->getMessage() . "\n";
    }
}

echo "\nPlateau après les coups :\n";
echo $game->getBoard()->render();
