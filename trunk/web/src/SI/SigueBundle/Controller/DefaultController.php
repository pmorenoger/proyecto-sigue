<?php

namespace SI\SigueBundle\Controller;

use SI\SigueBundle\Entity\Alumno;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session;
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

            $profesor = $em
                        ->getRepository('SISigueBundle:Profesor')
                        //->findOneByCorreo(array('correo' => $correo, 'password' => $pass));
                        ->findOneByCorreo($correo);
            
            $alumno = $this->getDoctrine()
                        ->getRepository('SISigueBundle:Alumnos')
                         //->findOneBy(array('correo' => $correo, 'password' => $pass));
                         ->findOneByCorreo($correo);
            
            $res = 0;
            if($profesor) {
            /*SI ES UN PROFESOR*/
                $encrypted_password = $profesor->getPassword();
                $salt = $profesor->getSalt();
                $hash = self::checkhashSSHA($salt, $pass);
                if ($encrypted_password == $hash){
                    //$session = $this->getRequest()->getSession();
                     $session = $this->container->get('session');
                    $session->set('idprofesor', $profesor->getIdprofesor());
                    $session->set('pProf',$pass);
                    //return $this->redirect('Profesor/inicio');
                    $res = 1;
                }
            }elseif($alumno){
                /*SI ES UN ALUMNO*/
                $encrypted_password = $alumno->getPassword();
                $salt = $alumno->getSalt();
                $hash = self::checkhashSSHA($salt, $pass);
                if ($encrypted_password == $hash){
                    //$session = $this->getRequest()->getSession();
                     $session = $this->container->get('session');
                    $session->set('idalumno', $alumno->getIdalumno());
                    $session->set('pAl',$pass);
                    //return $this->redirect('Alumno/inicio');
                    $res = 2;
                }
            }//else{
            /*SI NO ES UN ALUMNO TAMPOCO: REDIRECT A INICIO*/
                 //return $this->render('SISigueBundle:Default:error.html.php');               
            //}
            if ($res == 1) return $this->redirect('Profesor/inicio');
            else if ($res == 2) return $this->redirect('Alumno/inicio');
            return $this->render('SISigueBundle:Default:error.html.php'); 
    }
    
    private function checkhashSSHA($salt, $password) {
        $hash = base64_encode(sha1($password . $salt, true) . $salt);
        return $hash;

    }
    
    public function logoutAction(){
       // $session = $this->getRequest()->getSession();        
         $session = $this->container->get('session');
        $session->remove('idalumno');
        $session->remove('idprofesor');
        return $this->redirect('inicio');
        
    }
}
