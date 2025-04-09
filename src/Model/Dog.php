<?php

namespace App\Model;

class Dog {
  public function __construct(
    private int $id,
    private string $name,
    private string $breed,
    private int $age,
  ) {

  }

  public function getId(): int {
    return $this->id;
  }

  public function getName(): string {
    return $this->name;
  }

  public function getBreed(): string {
    return $this->breed;
  }

  public function getAge(): int {
    return $this->age;
  }
}