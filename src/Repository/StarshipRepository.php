<?php

namespace App\Repository;

use App\Model\Starship;
use Psr\Log\LoggerInterface;

class StarshipRepository {

  public function __construct(private LoggerInterface $logger) {
    $this->logger->info('Starships Retrieved!');
  }

  public function findAll(): array {

    return [
      new Starship(
          1,
          'USS LeafyCruiser (NCC-0001)',
          'Garden',
          'Jean-Luc Pickles',
          'taken over by Q'
      ),
      new Starship(
          2,
          'USS Espresso (NCC-1234-C)',
          'Garden',
          'Jean-Luc Pickles',
          'taken over by Q'
      ),
      new Starship(
          3,
          'USS Wanderlust (NCC-2024-W)',
          'Garden',
          'Jean-Luc Pickles',
          'taken over by Q'
      ),
    ];
  }

  public function find(int $id): ?Starship {
    foreach ($this->findAll() as $starship) {
      if ($starship->getId() === $id) {
        return $starship;
      }
    }
    return null;
  }
}