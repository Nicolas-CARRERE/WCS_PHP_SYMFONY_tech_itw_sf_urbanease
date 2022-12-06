<?php

namespace App\Controller;

use App\Entity\Tile;
use App\Repository\BoatRepository;
use App\Services\MapManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MapController extends AbstractController
{
    /**
     * @Route("/map", name="map")
     */
    public function displayMap(BoatRepository $boatRepository): Response
    {
        $em = $this->getDoctrine()->getManager();
        $tiles = $em->getRepository(Tile::class)->findAll();

        foreach ($tiles as $tile) {
            $map[$tile->getCoordX()][$tile->getCoordY()] = $tile;
        }

        $boat = $boatRepository->findOneBy([]);

        return $this->render('map/index.html.twig', [
            'map' => $map ?? [],
            'boat' => $boat,
        ]);
    }
    /**
     * @Route("/start", name="start")
     */
    public function start(BoatRepository $boatRepository, MapManager $mapManager): Response
    {
        $em = $this->getDoctrine()->getManager();
        $tiles = $em->getRepository(Tile::class)->findAll();

        foreach ($tiles as $tile) {
            $map[$tile->getCoordX()][$tile->getCoordY()] = $tile;
        }

        $boat = $boatRepository->findOneBy([]);
        //init the boats position to (0,0)
        $boat->setCoordX(0);
        $boat->setCoordY(0);
        $em->flush();

        //get a random island for the treasure and reset the previous one
        $deleteTreasure = $mapManager->findOneBy(['hasTreasure' => 'true']);
        if ($deleteTreasure) {
            $deleteTreasure->setHasTreasure(false);
        }
        $em->flush();
        $mapManager->getRandomIsland()->setHasTreasure(true);
        $em->flush();

        return $this->render('map/index.html.twig', [
            'map' => $map ?? [],
            'boat' => $boat,
        ]);
    }
}
