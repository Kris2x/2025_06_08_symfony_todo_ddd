<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Random\RandomException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LuckyController
{
  /**
   * @throws RandomException
   */
  #[Route('/lucky/number')]
  public function number(): Response
  {
    $number = random_int(0, 100);

    return new Response(
      '<html><body>Lucky number: ' . $number . '</body></html>'
    );
  }
}
