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
        // Rellenamos el objeto con información de prueba
        // $apuesta->setTexto('2 13 34 44 48');
        // $apuesta->setFecha(new \DateTime('tomorrow'));

        $form = $this->createFormBuilder($apuesta)
            ->add('texto', TextType::class)
            ->add('fecha', DateType::class)
            ->add('save', SubmitType::class, array('label' => 'Añadir Apuesta'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // De esta manera podemos rellenar la variable
            // $apuesta con los datos del formulario.
            $apuesta = $form->getData();

            // Obtenemos el gestor de entidades de Doctrine
            $entityManager = $this->getDoctrine()->getManager();

            // Le decimos a doctrine que nos gustaría almacenar
            // el objeto de la variable en la base de datos
            $entityManager->persist($apuesta);

            // Ejecuta las consultas necesarias
            $entityManager->flush();

            //Redirigimos a una página de confirmación.
            return $this->redirectToRoute('app_apuesta_creada');
        }

        return $this->render('sorteo/nuevaApuesta.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    public function apuestaCreada()
    {
        return $this->render('sorteo/apuestaCreada.html.twig');
    }
}