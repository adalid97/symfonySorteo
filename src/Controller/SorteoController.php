<?php
// src/Controller/SorteoController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SorteoController extends AbstractController
{
    public function index()
    {
        return $this->render('sorteo/index.html.twig');
    }

    public function numero($maximo)
    {
        if (!is_numeric($maximo) || floatval($maximo) != intval($maximo)) {
            return $this->redirectToRoute('app_numero_sorteo', array('maximo' => 0));
        }

        $numero = random_int(0, $maximo);

        return $this->render('sorteo/numero.html.twig', [
            'numero' => $numero,
            'maximo' => $maximo,
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
    
    public function euromillones() {
        $apuesta = array();
        for ($i=0; $i<5; $i++) {
            do {
                $numero = random_int(1, 50);
            } while (in_array($numero, $apuesta));
            $apuesta[] = $numero;
        }
        for ($i=0; $i<2; $i++) {
            do {
                $numero = random_int(1, 12);
            } while (in_array($numero, $apuesta));
            $apuesta[] = $numero;
        }
        return $this->render('sorteo/euromillones.html.twig', ['apuesta' => $apuesta]);
    }
}