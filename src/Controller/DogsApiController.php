<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/dogs')]
final class DogsApiController extends AbstractController
{

    private static $dogs = array(
        array('id' => 1, 'wiek' => 5, 'rasa' => 'buldog'),
        array('id' => 2, 'wiek' => 3, 'rasa' => 'spaniel'),
        array('id' => 3, 'wiek' => 2, 'rasa' => 'buldog'),
        array('id' => 4, 'wiek' => 4, 'rasa' => 'collie'),
        array('id' => 5, 'wiek' => 1, 'rasa' => 'chihuahua')
      );

    #[Route('')]
    public function getDogs(): Response
    {
        return $this->json(self::$dogs);
        /* return $this->render('dogs_api/index.html.twig', [
            'controller_name' => 'DogsApiController',
        ]); */
    }

    #[Route('/{id}')]
    public function getDog(int $id): Response
    {
        foreach(self::$dogs as $dog) {
            if ($dog['id'] === $id) {
                return $this->json($dog);
            }
        }
        return $this->json(['message' => 'Przedmiot nie znaleziony'], Response::HTTP_NOT_FOUND);

    }
}
