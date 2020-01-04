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

    public function verApuesta($id)
    {
        // Obtenemos el gestor de entidades de Doctrine
        $entityManager = $this->getDoctrine()->getManager();

        /* Obtenenemos el repositorio de Apuestas y 
           buscamos en el usando la id de la apuesta */
        $apuesta= $entityManager->getRepository(Apuesta::class)->find($id);

        // Si la apuesta no existe lanzamos una excepción.
        if (!$apuesta){
            throw $this->createNotFoundException(
                'No existe ninguna apuesta con id '.$id
            );
        }

        /* Pasamos la apuesta a una plantilla que
           se encargue de mostrar sus datos. */
        return $this->render('sorteo/verApuesta.html.twig', array(
            'apuesta' => $apuesta,
        ));
    }
    public function listaApuestas()
    {
        // Obtenemos el gestor de entidades de Doctrine
        $entityManager = $this->getDoctrine()->getManager();

        // obtenemos todas las apuestas
        $apuestas= $entityManager->getRepository(Apuesta::class)->findAll();

        return $this->render('sorteo/listaApuestas.html.twig', array(
            'apuestas' => $apuestas,
        ));
    }
    public function editarApuesta(Request $request, $id)
    {
        // Obtenemos el gestor de entidades de Doctrine
        $entityManager = $this->getDoctrine()->getManager();

        // Obtenenemos el repositorio de Apuestas y buscamos en el usando la id de la apuesta
        $apuesta = $entityManager->getRepository(Apuesta::class)->find($id);

        // Si la apuesta no existe lanzamos una excepción.
        if (!$apuesta){
            throw $this->createNotFoundException(
                'No existe ninguna apuesta con id '.$id
            );
        }

        // Creamos el formulario a partir de $apuesta
        $form = $this->createFormBuilder($apuesta)
            ->add('texto', TextType::class)
            ->add('fecha', DateType::class)
            ->add('save', SubmitType::class, array('label' => 'Editar Apuesta'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // De esta manera podemos sobreescribir la variable $apuesta con los datos del formulario.
            $apuesta = $form->getData();

            // Ejecuta las consultas necesarias (UPDATE en este caso)
            $entityManager->flush();

            //Redirigimos a la página de ver la apuesta editada.
            return $this->redirectToRoute('app_apuesta_ver', array('id'=>$id));
        }

        return $this->render('sorteo/nuevaApuesta.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    public function borrarApuesta($id)
    {
        // Obtenemos el gestor de entidades de Doctrine
        $entityManager = $this->getDoctrine()->getManager();

        // Obtenenemos el repositorio de Apuestas y buscamos en el usando la id de la apuesta
        $apuesta= $entityManager->getRepository(Apuesta::class)->find($id);

        // Si la apuesta no existe lanzamos una excepción.
        if (!$apuesta){
            throw $this->createNotFoundException(
                'No existe ninguna apuesta con id '.$id
            );
        }
        $entityManager->remove($apuesta);
        $entityManager->flush();
        return $this->render('sorteo/apuestaBorrada.html.twig');
    }
}