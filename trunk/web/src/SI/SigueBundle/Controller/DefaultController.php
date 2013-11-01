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
            $pass = $request->request->get("password");
            
            $em = $this->getDoctrine()->getManager();
           
            
            
            var_dump($correo);    
            var_dump($pass);
            $profesor = $em
                        ->getRepository('SISigueBundle:Profesor')
                        ->findOneByCorreo(array('correo' => $correo, 'password' => $pass));
            
            
            var_dump($profesor);
            $alumno = $this->getDoctrine()
                        ->getRepository('SISigueBundle:Alumnos')
                         ->findOneBy(array('correo' => $correo, 'password' => $pass));
            
            
            
            if($profesor) {
            /*SI ES UN PROFESOR*/
                return $this->redirect('Profesor/perfil');
            }elseif($alumno){
                /*SI ES UN ALUMNO*/
                 return $this->redirect('Alumno/perfil');                
            }else{
            /*SI NO ES UN ALUMNO TAMPOCO: REDIRECT A INICIO*/
                 return $this->render('SISigueBundle:Default:error.html.php');               
            }
        
    }
    
    
}
