<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/')]
    public function homepage(): Response
    {

        $starshipCount = 400;

        // tablica asocjacyjna
        $myShip = [
            'name' => 'USS LeafyCruiser',
            'class' => 'Garden',
            'captain' => 'Michael Jackson',
            'status' => 'still building...',
        ];

        return $this->render('main/homepage.html.twig', [
            'numberOfStarships' => $starshipCount,
            'myShip' => $myShip,
        ]);
        // return new Response('<strong>Hello</hello> world!');
    }
}
