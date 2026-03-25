<?php

namespace App\Controller;

use App\Services\MapManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Tile;
use App\Repository\BoatRepository;

class MapController extends AbstractController
{
    /**
     * @Route("/map", name="map")
     */
    public function displayMap(BoatRepository $boatRepository, EntityManagerInterface $em) :Response
    {
        $tiles = $em->getRepository(Tile::class)->findAll();

        $map = [];
        foreach ($tiles as $tile) {
            $map[$tile->getCoordX()][$tile->getCoordY()] = $tile;
        }

        $boat = $boatRepository->findOneBy([]);
        if (!$boat) {
            throw $this->createNotFoundException('No boat found');
        }

        return $this->render('map/index.html.twig', [
            'map'  => $map,
            'boat' => $boat,
        ]);
    }

    /**
     * Start a new game: reset boat and place treasure
     * @Route("/start", name="start")
     */
    public function start(BoatRepository $boatRepository, EntityManagerInterface $em, MapManager $mapManager): Response
    {
        $boat = $boatRepository->findOneBy([]);
        if (!$boat) {
            throw $this->createNotFoundException('No boat found');
        }
        $boat->setCoordX(0);
        $boat->setCoordY(0);

        // Clear old treasures
        $tiles = $em->getRepository(Tile::class)->findAll();
        foreach ($tiles as $tile) {
            if ($tile->getHasTreasure()) {
                $tile->setHasTreasure(false);
            }
        }

        // Place new treasure on random island
        $randomIsland = $mapManager->getRandomIsland();
        if ($randomIsland) {
            $randomIsland->setHasTreasure(true);
        }

        $em->flush();

        return $this->redirectToRoute('map');
    }
}
