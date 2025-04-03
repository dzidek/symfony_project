<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class MainController {

  #[Route('/')]
  public function homepage() {
    return new Response('<strong>Hello</hello> world!');
  }
}