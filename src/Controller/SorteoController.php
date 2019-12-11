<?php
// src/Controller/SorteoController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SorteoController extends AbstractController
{
    public function numero()
    {
        $numero = random_int(0, 100);

        return $this->render('sorteo/numero.html.twig', [
            'numero' => $numero,
        ]);
    }
}