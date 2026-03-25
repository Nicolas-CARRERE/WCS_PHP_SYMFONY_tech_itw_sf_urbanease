<?php

namespace App\Controller;

use App\Entity\Boat;
use App\Form\BoatType;
use App\Repository\BoatRepository;
use App\Services\MapManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/boat")
 */
class BoatController extends AbstractController
{

    /**
     * Move the boat to coord x,y
     * @Route("/move/{x}/{y}", name="moveBoat", requirements={"x"="\d+", "y"="\d+"})
     */
    public function moveBoat(int $x, int $y, BoatRepository $boatRepository, EntityManagerInterface $em) :Response
    {
        $boat = $boatRepository->findOneBy([]);
        if (!$boat) {
            throw $this->createNotFoundException('No boat found');
        }
        $boat->setCoordX($x);
        $boat->setCoordY($y);

        $em->flush();

        return $this->redirectToRoute('map');
    }

    /**
     * Move the boat by direction (N, S, E, W)
     * @Route("/direction/{direction}", name="moveDirection", requirements={"direction"="[NSEW]"})
     */
    public function moveDirection(string $direction, BoatRepository $boatRepository, EntityManagerInterface $em, MapManager $mapManager): Response
    {
        $boat = $boatRepository->findOneBy([]);
        if (!$boat) {
            throw $this->createNotFoundException('No boat found');
        }
        
        $newX = $boat->getCoordX();
        $newY = $boat->getCoordY();
        
        switch ($direction) {
            case 'N':
                $newY = $boat->getCoordY() - 1; // North = up on screen (decrease Y)
                break;
            case 'S':
                $newY = $boat->getCoordY() + 1; // South = down on screen (increase Y)
                break;
            case 'E':
                $newX = $boat->getCoordX() + 1;
                break;
            case 'W':
                $newX = $boat->getCoordX() - 1;
                break;
        }

        if (!$mapManager->tileExists($newX, $newY)) {
            $this->addFlash('error', 'You cannot move there!');
            return $this->redirectToRoute('map');
        }

        $boat->setCoordX($newX);
        $boat->setCoordY($newY);

        $em->flush();

        if ($mapManager->checkTreasure($boat)) {
            $this->addFlash('success', 'Treasure found! You win!');
        }

        return $this->redirectToRoute('map');
    }


    /**
     * @Route("/", name="boat_index", methods="GET")
     */
    public function index(BoatRepository $boatRepository): Response
    {
        return $this->render('boat/index.html.twig', ['boats' => $boatRepository->findAll()]);
    }

    /**
     * @Route("/new", name="boat_new", methods="GET|POST")
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $boat = new Boat();
        $form = $this->createForm(BoatType::class, $boat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($boat);
            $em->flush();

            return $this->redirectToRoute('boat_index');
        }

        return $this->render('boat/new.html.twig', [
            'boat' => $boat,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="boat_show", methods="GET")
     */
    public function show(Boat $boat): Response
    {
        return $this->render('boat/show.html.twig', ['boat' => $boat]);
    }

    /**
     * @Route("/{id}/edit", name="boat_edit", methods="GET|POST")
     */
    public function edit(Request $request, Boat $boat, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(BoatType::class, $boat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('boat_index', ['id' => $boat->getId()]);
        }

        return $this->render('boat/edit.html.twig', [
            'boat' => $boat,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="boat_delete", methods="DELETE")
     */
    public function delete(Request $request, Boat $boat, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $boat->getId(), $request->request->get('_token'))) {
            $em->remove($boat);
            $em->flush();
        }

        return $this->redirectToRoute('boat_index');
    }
}
