<?php

namespace SI\SigueBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SI\SigueBundle\Entity\Alumno;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SISigueBundle:Default:index.html.php');
    }
    
    public function loginAction(){
        
         /*AQUI TENGO QUE CONTROLAR LA INFO DEL LOGIN*/
            
            $request = Request::createFromGlobals();
            
            
            $correo = $request->request->get("user");
            $pass = $request->request->get("pass");
            
            $em = $this->getDoctrine()->getManager();
           
            
            
            var_dump($correo);    
            $profesor = $em
                        ->getRepository('SISigueBundle:Profesor')
                        ->findOneByCorreoAndPass($correo,$pass);
            var_dump($profesor);
            $alumno = $this->getDoctrine()
                        ->getRepository('SISigueBundle:Alumnos')
                         ->findOneBy(array('correo' => $correo, 'password' => $pass));
            
            
            
            if($profesor) {
            /*SI ES UN PROFESOR*/
                return $this->render('SISigueBundle:Profesor:index.html.php');
            }elseif($alumno){
                /*SI ES UN ALUMNO*/
                 return $this->render('SISigueBundle:Alumno:index.html.php');                
            }else{
            /*SI NO ES UN ALUMNO TAMPOCO: REDIRECT A INICIO*/
            // return $this->redirect('inicio');
                echo"CAGADA";
            }
        
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
