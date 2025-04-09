<?php

namespace App\Repository;

use App\Model\Dog;
use Psr\Log\LoggerInterface;

class DogRepository {

  // Prywatna właściwość do przechowywania psów (symulacja bazy danych)
  private array $dogsStorage = [];

  public function __construct(private LoggerInterface $logger) {
    // Inicjalizujemy magazyn początkowymi danymi przy tworzeniu repozytorium
    $this->initializeDogs();
    $this->logger->info('DogRepository initialized with ' . count($this->dogsStorage) . ' dogs.');
  }

  // Prywatna metoda do inicjalizacji danych
  private function initializeDogs(): void {
    $initialDogs = [
       new Dog(1, 'Burek','Mieszaniec', 5),
       new Dog(2, 'Luna','Labrador Retriever', 2),
       new Dog(3, 'Max','Owczarek Niemiecki', 7),
       new Dog(4, 'Sonia','Beagle', 3),
       new Dog(5, 'Rocky','Buldog Francuski', 1),
       new Dog(6, 'Fafik','Jamnik', 10),
       new Dog(7, 'Nela','Golden Retriever', 4),
    ];
    foreach ($initialDogs as $dog) {
        $this->dogsStorage[$dog->getId()] = $dog;
    }
  }

  /**
  * Zwraca wszystkie psy z magazynu.
  * @return Dog[]
  */
  public function findAll(): array {
    $this->logger->info('Fetching all dogs.');
    return array_values($this->dogsStorage); // Zwraca tablicę bez kluczy ID
  }

  /**
  * Znajduje psa po jego ID.
  * @param int $id
  * @return Dog|null
  */
  public function find(int $id): ?Dog {
    $this->logger->info('Finding dog with ID: ' . $id);
    return $this->dogsStorage[$id] ?? null; // Zwraca psa lub null, jeśli ID nie istnieje
  }

  /**
  * Dodaje nowego psa do magazynu.
  * @param Dog $dogToAdd Obiekt psa do dodania.
  * @return void
  * @throws InvalidArgumentException Jeśli pies o danym ID już istnieje.
  */
  public function add(Dog $dogToAdd): void {
    $id = $dogToAdd->getId();

    if (isset($this->dogsStorage[$id])) {
        $errorMessage = 'Dog with ID ' . $id . ' already exists.';
        $this->logger->error($errorMessage);
        // W API lepiej rzucić wyjątek, kontroler go obsłuży
        throw new InvalidArgumentException($errorMessage);
    }

    $this->logger->info('Adding dog with ID: ' . $id);
    $this->dogsStorage[$id] = $dogToAdd;
    // W realnej aplikacji tutaj byłby zapis do bazy danych ($entityManager->persist($dogToAdd); $entityManager->flush();)
  }
}