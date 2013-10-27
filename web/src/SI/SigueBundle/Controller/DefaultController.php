<?php

namespace SI\SigueBundle\Controller;


use SI\SigueBundle\Entity\Alumno;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SISigueBundle:Default:index.html.php');
    }
    
    public function createAction()
{
    $alumno = new Alumno();
    $alumno->setNombre("Diego");
    $alumno->setDni("70820622B");
    $alumno->setApellidos(' es el puto amo');
    $alumno->setCorreo("diego.santos.garcia@ucm.es");
    $alumno->setPassword("diego");
    
    $em = $this->getDoctrine()->getManager();
    $em->persist($alumno);
    $em->flush();

    return $this->render('SISigueBundle:Default:prueba.html.php', array("idalumno"=>$alumno->getIdalumno()));
}
}
