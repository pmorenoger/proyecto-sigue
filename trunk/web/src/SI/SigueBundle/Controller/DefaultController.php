<?php

namespace SI\SigueBundle\Controller;

use SI\SigueBundle\Entity\Alumno;
use SI\SigueBundle\Entity\Profesor;
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
            
            $admin = $this->getDoctrine()
                        ->getRepository('SISigueBundle:Admin')
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
            }elseif ($admin){
            /*Si es un admin, que tenga una interfaz sencillita que permita añadir un profesor*/
                $session = $this->container->get('session');
                $session->set('idadmin', $admin->getIdadmin());
                $session->set('padmin',$pass);
                $res = 3;
            }
            if ($res == 1) return $this->redirect('Profesor/inicio');
            else if ($res == 2) return $this->redirect('Alumno/inicio');
            else if ($res == 3) return $this->redirect('admin');
            return $this->render('SISigueBundle:Default:error.html.php'); 
    }
    
    private function checkhashSSHA($salt, $password) {
        $hash = base64_encode(sha1($password . $salt, true) . $salt);
        return $hash;

    }
    
    public function adminAction(){
         $admin = self::getAdmin();
            if($admin === "session_lost"){
               $session = $this->container->get('session');
                $session->remove('idalumno');
                $session->remove('idprofesor');
                $session->remove('idmin');
                return $this->redirect('inicio');
            }
        
         return $this->render('SISigueBundle:Default:admin.html.php', array("exito" => false));  
    }
    
    
    public function logoutAction(){
       // $session = $this->getRequest()->getSession();        
         $session = $this->container->get('session');
        $session->remove('idalumno');
        $session->remove('idprofesor');
        return $this->redirect('inicio');
        
    }
    
    
    private function getAdmin(){
                $peticion = $this->container->get('session');
                $id = $peticion->get('idadmin');
                if(!isset($id) || is_null($id)){
                    return "session_lost";
                }
                $em = $this->getDoctrine()->getManager();
                $admin = $em->getRepository('SISigueBundle:Admin')->findOneByIdadmin($id);
                return $admin;
            }
            
    public function add_profesorAction(){
        //Añado un profesor al sistema.
        $request = Request::createFromGlobals();
        $profesor = new Profesor();
        $nombre = $request->request->get("nombre");
        $apellidos = $request->request->get("apellidos");
        $correo = $request->request->get("correo");
        
        $profesor->setNombre($nombre);
        $profesor->setCorreo($correo);
        $profesor->setApellidos($apellidos);
        //var_dump($profesor);die();
        $pass_provisional = explode("@",$profesor->getCorreo());
        $pass_provisional[0] = $pass_provisional[0].rand(0,99);

        $hash = self::hashSSHA($pass_provisional[0]);
        $encrypted_password = $hash["encrypted"];
        $salt = $hash["salt"];
        $profesor->setSalt($salt);
        $profesor->setPassword($encrypted_password);
        
        
        $message = \Swift_Message::newInstance('ssl://smtp.gmail.com', 465)
        ->setSubject('Alta Usuario SIGUE')
        ->setFrom('admin@sigue.com')
        ->setTo($correo)
        ->setBody('Ha sido añadido al sistema SIGUE de la ucm.\n 
            Su usuario es esta dirección de correo y su password es: '. $pass_provisional[0] );
        $this->get('mailer')->send($message);
                        
        $em = $this->getDoctrine()->getManager();
        $em->persist($profesor);
        $em->flush();
        
        
        
        return $this->render('SISigueBundle:Default:admin.html.php', array("exito" => true));        
    }
    
    
      private function hashSSHA($password) {
        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
        
        }
}
