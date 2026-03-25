<?php

namespace App\Services;

use App\Entity\Boat;
use App\Entity\Tile;
use App\Repository\TileRepository;

class MapManager
{
    private $tileRepository;

    public function __construct(TileRepository $tileRepository)
    {
        $this->tileRepository = $tileRepository;
    }

    public function tileExists(int $x, int $y): bool
    {
        $tile = $this->tileRepository->findOneBy(['coordX' => $x, 'coordY' => $y]);
        return $tile !== null;
    }

    public function getRandomIsland(): ?Tile
    {
        $islandTiles = $this->tileRepository->findBy(['type' => 'island']);
        
        if (empty($islandTiles)) {
            return null;
        }
        
        $randomKey = array_rand($islandTiles);
        return $islandTiles[$randomKey];
    }

    public function checkTreasure(Boat $boat): bool
    {
        $tile = $this->tileRepository->findOneBy(['coordX' => $boat->getCoordX(), 'coordY' => $boat->getCoordY()]);
        
        return $tile !== null && $tile->getHasTreasure() === true;
    }
}
