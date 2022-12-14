<?php

namespace App\Services;

use App\Entity\Boat;
use App\Entity\Tile;
use App\Repository\TileRepository;

class MapManager extends TileRepository
{
    public function tileTypeOf(int $x, int $y): ?string
    {
        // Here, we will check the type of the tile according to its coordinates
        $db = $this->findOneBy(['coordX' => $x, 'coordY' => $y])->getType();
        return $db;

    }
    public function tileExists(int $x, int $y): bool
    {
        // Here, we will check if the boat is in the map
        // Map limits are set dynamically according to the database
        $db = $this->findBy([], ['coordX' => 'DESC'], limit:1, offset:0);
        $maxX = $db[0]->getCoordX();

        $db = $this->findBy([], ['coordX' => 'ASC'], limit:1, offset:0);
        $minX = $db[0]->getCoordX();

        $db = $this->findBy([], ['coordY' => 'DESC'], limit:1, offset:0);
        $maxY = $db[0]->getCoordY();

        $db = $this->findBy([], ['coordY' => 'ASC'], limit:1, offset:0);
        $minY = $db[0]->getCoordY();

        if (($x >= $minX && $x <= $maxX) && ($y >= $minY && $y <= $maxY)) {
            return true;
        }
        return false;

    }
    public function getRandomIsland(): ?Tile
    {
        // Here, we will look for a random island
        $islands = $this->findBy(['type' => 'island']);
        $randomIslandById = array_rand($islands);
        $randomIsland = $islands[$randomIslandById];
        return $randomIsland;
    }
    public function checkTreasure(Boat $boat): ?bool
    {
        // Here, we will check if the boat is on the tile with the treasure
        return $this->findOneBy(['coordX' => $boat->getCoordX(), 'coordY' => $boat->getCoordY()])->getHasTreasure();
    }
}
