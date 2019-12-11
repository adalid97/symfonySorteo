<?php
// src/Controller/SorteoController.php
namespace App\Controller;

use App\Entity\Apuesta;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
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

    public function nuevaApuesta(Request $request)
    {
        
        $apuesta = new Apuesta();
        /* Descomenta las siguientes líneas para rellenar la apuesta
           con información de prueba en lugar de estar vacía */
        // $apuesta->setTexto('2 13 34 44 48'); 
        // $apuesta->setFecha(new \DateTime('tomorrow'));

        $form = $this->createFormBuilder($apuesta)
            ->add('texto', TextType::class)
            ->add('fecha', DateType::class)
            ->add('save', SubmitType::class, array('label' => 'Añadir Apuesta'))
            ->getForm();

        return $this->render('sorteo/nuevaApuesta.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}