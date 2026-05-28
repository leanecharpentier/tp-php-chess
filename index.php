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

$moves = [
    new Move(new Position(6, 4), new Position(4, 4)), // 1. e2-e4 (blanc)
    new Move(new Position(1, 4), new Position(3, 4)), // 1... e7-e5 (noir)
    new Move(new Position(7, 5), new Position(4, 2)), // 2. Fc4 (blanc)
    new Move(new Position(1, 0), new Position(2, 0)), // 2... a7-a6 (noir, neutre)
    new Move(new Position(7, 3), new Position(3, 7)), // 3. Dh5 (blanc)
    new Move(new Position(2, 0), new Position(3, 0)), // 3... a6-a5 (noir, neutre)
    new Move(new Position(3, 7), new Position(1, 5)), // 4. Dxf7+ (blanc) → ÉCHEC !
    new Move(new Position(1, 1), new Position(2, 1)), // 4... b7-b6 (noir, ignore l'échec)
];


foreach ($moves as $move) {
    try {
        $game->play($move);
        if ($game->isCheckmate($game->getCurrentPlayer())) {
            echo "MAT ! Le joueur " . $game->getCurrentPlayer()->value . " a perdu.\n";
        } elseif ($game->isCheck($game->getCurrentPlayer())) {
            echo "Échec sur le joueur " . $game->getCurrentPlayer()->value . " !\n";
        }
    } catch (ChessException $e) {
        echo "Coup refusé : " . $e->getMessage() . "\n";
    }
}

echo "\nPlateau après les coups :\n";
echo $game->getBoard()->render();
