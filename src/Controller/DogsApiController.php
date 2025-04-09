<?php

namespace App\Controller;

use App\Model\Dog; // Dodaj import dla Dog
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request; // Dodaj import dla Request
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse; // Dodaj import dla JsonResponse
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\DogRepository;
use InvalidArgumentException; // Do łapania błędów z repozytorium
use TypeError; // Do łapania błędów tworzenia obiektu

#[Route('/api/dogs')]
class DogsApiController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function getAll(DogRepository $repository): Response
    {
        $dogs = $repository->findAll();
        // Używamy grup serializacji, jeśli chcemy kontrolować co jest zwracane
        return $this->json($dogs);
    }

    /**
    * Dodaje nowego psa. Oczekuje danych JSON w ciele żądania.
    * Przykład JSON: {"id": 8, "name": "Rex", "breed": "Terier", "age": 4}
    */
    #[Route('', methods: ['POST'])]
    public function add(Request $request, DogRepository $repository): Response
    {
        // 1. Pobierz dane z ciała żądania (zakładamy JSON)
        try {
            // true jako drugi argument dekoduje JSON do tablicy asocjacyjnej
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return $this->json(['status' => 'error', 'message' => 'Invalid JSON body: ' . $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        // 2. Podstawowa Walidacja Danych (w realnej aplikacji użyj komponentu Validator!)
        $errors = [];
        if (!isset($data['id']) || !is_int($data['id']) || $data['id'] <= 0) {
            $errors[] = 'Pole "id" jest wymagane i musi być dodatnią liczbą całkowitą.';
        }
        if (empty($data['name']) || !is_string($data['name'])) {
            $errors[] = 'Pole "name" jest wymagane i musi być tekstem.';
        }
        if (empty($data['breed']) || !is_string($data['breed'])) {
            $errors[] = 'Pole "breed" jest wymagane i musi być tekstem.';
        }
        if (!isset($data['age']) || !is_int($data['age']) || $data['age'] < 0) {
            $errors[] = 'Pole "age" jest wymagane i musi być nieujemną liczbą całkowitą.';
        }

        if (!empty($errors)) {
            return $this->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $errors
            ], Response::HTTP_BAD_REQUEST); // 400 Bad Request
        }

        // 3. Stwórz obiekt Dog
        try {
            $newDog = new Dog(
                $data['id'],
                trim($data['name']),
                trim($data['breed']),
                $data['age']
            );
        } catch (TypeError $e) {
                return $this->json([
                    'status' => 'error',
                    'message' => 'Error creating Dog object: ' . $e->getMessage()
                ], Response::HTTP_BAD_REQUEST);
        }


        // 4. Dodaj psa używając repozytorium
        try {
            $repository->add($newDog);
        } catch (InvalidArgumentException $e) {
            // Obsługa błędu z repozytorium (np. duplikat ID)
                return $this->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], Response::HTTP_CONFLICT); // 409 Conflict
        } catch (\Exception $e) {
            // Inne, nieoczekiwane błędy repozytorium/zapisu
                return $this->json([
                    'status' => 'error',
                    'message' => 'An unexpected error occurred: ' . $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR); // 500 Internal Server Error
        }

        // 5. Zwróć odpowiedź sukcesu
        // Zwracamy status 201 Created i opcjonalnie nowo utworzony obiekt
        return $this->json(
            [
                'status' => 'success',
                'message' => 'Dog added successfully!',
                'dog' => $newDog // Zwrócenie obiektu wymaga, aby był on serializowalny (publiczne właściwości lub gettery)
            ],
            Response::HTTP_CREATED // 201 Created
        );
    }

    #[Route('/{id<\d+>}', methods: ['GET'])]
    public function get(int $id, DogRepository $repository): Response {
        $dog = $repository->find($id);

        if (!$dog) {
            throw $this->createNotFoundException('Dog not found');
        }

        return $this->json($dog);
    }
}