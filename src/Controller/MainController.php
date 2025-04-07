<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Repository\StarshipRepository;

class MainController extends AbstractController
{
    #[Route('/')]
    public function homepage(StarshipRepository $repository): Response
    {
        $ships = $repository->findAll();
        $starshipCount = count($ships);

        $myShip = $ships[array_rand($ships)];

        return $this->render('main/homepage.html.twig', [
            'numberOfStarships' => $starshipCount,
            'myShip' => $myShip,
        ]);
        // return new Response('<strong>Hello</hello> world!');
    }
}
