<?php

namespace SI\SigueBundle\Controller;

use SI\SigueBundle\Entity\Alumnos;
use SI\SigueBundle\Entity\Codigos;
use SI\SigueBundle\Entity\AsignaturaAlumno;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AlumnoController extends Controller
{
    public function perfilAction()
    {  
        $peticion = $this->getRequest()->getSession();
        $id = $peticion->get('idalumno');
        $em = $this->getDoctrine()->getEntityManager();
        $alumno = $em->getRepository('SISigueBundle:Alumnos')->find($id);
        
        if ($alumno->getCodigo_id() === NULL){
            $alumno->setCodigo_id($alumno->getCorreo()."#&".$alumno->getPassword());
            $em->persist($alumno);
            $em->flush();
            
            //guardamos el código generado en la BBDD
            $codigo = new Codigos();
            $codigo->setCodigo($alumno->getCodigo_id());
            $em2 = $this->getDoctrine()->getEntityManager();
            $em2->persist($codigo);
            $em2->flush();
        }
        
        $asignaturas = $em->getRepository('SISigueBundle:AsignaturaAlumno')->findBy(array('idAlumno' => $id));
        $asig = array();
        foreach ($asignaturas as $a){
            array_push($asig, $a->getIdAsignatura());
        }
        
        return $this->render('SISigueBundle:Alumno:perfil.html.php',array('alumno' => $alumno,'asignaturas' =>$asig));
    }
    
    public function registrarAction($id,$asig){
        //obtenenos el alumno y la asignatura con que realziaremos las diferentes opciones
        $em = $this->getDoctrine()->getEntityManager();
        $alumno = $em->getRepository('SISigueBundle:Alumnos')->find($id);
        $asignatura = $em->getRepository('SISigueBundle:Asignaturas')->find($asig);
        
        /*$session = $this->getRequest()->getSession();
        $array = array('al'=>$alumno,'asig'=>$asignatura);
        $session->set('datos', $array);*/
        
        return $this->render('SISigueBundle:Alumno:registrar.html.php',array('alumno' => $alumno,'asignatura'=>$asignatura,'res'=>false));
    }
    
    public function tokenAction($id,$asig){
        $res = true;
        
        $request = Request::createFromGlobals();
        $token = $request->request->get("codigo");
        $em = $this->getDoctrine()->getEntityManager();
        $alumno = $em->getRepository('SISigueBundle:Alumnos')->find($id);
        $asignatura = $em->getRepository('SISigueBundle:Asignaturas')->find($asig);
        /*$peticion = $this->getRequest();
        $alumno = $peticion->get('alumno');
        $asignatura = $peticion->get('alumno');*/
        return $this->render('SISigueBundle:Alumno:registrar.html.php',array('alumno' => $alumno,'asignatura'=>$asignatura,'res'=>$res));
        //operaciones
    }
}
?>
