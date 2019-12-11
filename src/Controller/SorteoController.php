<?php
// src/Controller/SorteoController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SorteoController extends AbstractController
{
    public function numero($maximo)
    {
        $numero = random_int(0, $maximo);

        return $this->render('sorteo/numero.html.twig', [
            'numero' => $numero,
        ]);
    }

    public function suma($num1, $num2)
    {
        $resultado = $num1 + $num2;

        return $this->render('sorteo/suma.html.twig', [
            'num1' => $num1,
            'num2' => $num2,
            'resultado' => $resultado,
        ]);
    }
}