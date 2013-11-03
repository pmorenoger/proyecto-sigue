<?php

namespace SI\SigueBundle\Controller;

use SI\SigueBundle\Entity\Alumno;
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
            $alumno->setCodigo_id($alumno->getCorreo()."_".$alumno->getPassword());
            $em->persist($alumno);
            $em->flush();
        }
        
        return $this->render('SISigueBundle:Alumno:perfil.html.php',array('alumno' => $alumno));
    }
           
    /*public function qrAction()
    {
        $peticion = $this->getRequest()->getSession();
        $alumno = $peticion->get('alumno');
        var_dump($alumno);
        if ($alumno->getCodigo_id() === NULL){
            $em = $this->getDoctrine()->getEntityManager();
            $alumno->setCodigo_id($alumno->getCorreo()."_".$alumno->getPassword());
            $em->persist($alumno);
            $em->flush();
            QRcode::png($alumno->getCodigo_id(), '../web/img/ejemplo.png',QR_ECLEVEL_H,8);
            $response = array('url' => "<img src='../../img/ejemplo.png'>", 'ok' => true);
            return new Response(json_encode($response));
        }
        
        return new Response(json_encode(array('ok' => false)));
    }*/
}
?>
