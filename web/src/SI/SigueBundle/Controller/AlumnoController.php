<?php

namespace SI\SigueBundle\Controller;

use SI\SigueBundle\Entity\Alumnos;
use SI\SigueBundle\Entity\Codigos;
use SI\SigueBundle\Entity\AsignaturaAlumno;
use SI\SigueBundle\Entity\AsignaturaCodigo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AlumnoController extends Controller
{
    public function perfilAction()
    {  
        $peticion = $this->getRequest()->getSession();
        $id = $peticion->get('idalumno');
        $p = $peticion->get('pAl');
        $em = $this->getDoctrine()->getEntityManager();
        $alumno = $em->getRepository('SISigueBundle:Alumnos')->find($id);
        
        if ($alumno->getCodigo_id() === NULL){
            $alumno->setCodigo_id($alumno->getCorreo()."#&".$p);
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
        
        return $this->render('SISigueBundle:Alumno:registrar.html.php',array('alumno' => $alumno,'asignatura'=>$asignatura,'res'=>0,'est'=>NULL));
    }
    
    public function tokenAction($id,$asig){
        $res = 0;
        $em = $this->getDoctrine()->getEntityManager();
        
        $alumno = $em->getRepository('SISigueBundle:Alumnos')->find($id);
        $asignatura = $em->getRepository('SISigueBundle:Asignaturas')->find($asig);
        
        $request = Request::createFromGlobals();
        $token = $request->request->get("codigo");
        $codigo = self::tokenValido($em , $token, $asignatura); 
        if ( $codigo !== NULL ){
            //registramos el código para el alumno y la asignatura
            $a = $em->getRepository('SISigueBundle:AsignaturaAlumno')->findOneBy(array('idAsignatura'=> $asig,'idAlumno'=>$id));
            if (!is_null($a)){
                //actualizamos el codigo con la fecha de alta
                $date_time = new \DateTime();
                $codigo->setFechaAlta($date_time);
                $em->persist($codigo);
                $em->flush();
                //registramos el codigo en la tabla de asignatura-alumno-codigo
                $asig_cod = new AsignaturaCodigo();
                $asig_cod->setIdAsignaturaAlumno($a);
                $asig_cod->setIdCodigo($codigo);
                $em->persist($asig_cod);
                $em->flush();
                //actualizamos el número de tokens en la tabla asignatura alumno
                $a->setNum($a->getNum() + 1);
                $em->persist($a);
                $em->flush();
                //resultado positivo
                $res = 2;
            }else{
                $res = 1;
            }   
        }else{
            $res = 1;
        }
        
        return $this->render('SISigueBundle:Alumno:registrar.html.php',array('alumno' => $alumno,'asignatura'=>$asignatura,'res'=>$res,'est'=>NULL));
    }
    
    private function tokenValido($em,$token,$asignatura){
        if ($token !== ""){
            $codigo = $em->getRepository('SISigueBundle:Codigos')->findOneByCodigo($token);
            if ($codigo !== NULL){
                $asig_cod = $em->getRepository('SISigueBundle:AsignaturaCodigo')->findOneByIdCodigo($codigo);
                if ($asig_cod === NULL and $codigo->getId()->getId() == $asignatura->getId()){
                    return $codigo;
                }else return NULL;
            }else return NULL;
        }
        return NULL;
    }
    
    public function estadisticasAction($id,$asig){
        $est = NULL;
        $em = $this->getDoctrine()->getEntityManager();
        $alumno = $em->getRepository('SISigueBundle:Alumnos')->find($id);
        $asignatura = $em->getRepository('SISigueBundle:Asignaturas')->find($asig);
        if ($alumno and $asignatura){
            $a = $em->getRepository('SISigueBundle:AsignaturaAlumno')->findOneBy(array('idAsignatura'=> $asig,'idAlumno'=>$id));
            if($a){
                $array = self::numTotalToken($asig,$em,$a->getNum());
                $est = array('num' => $a->getNum(),'total' => $array['num'],'max' => $array['max'], 'mas' => $array['mas'], 'menos' => $array['menos']); 
            }
        }
        return $this->render('SISigueBundle:Alumno:registrar.html.php',array('alumno' => $alumno,'asignatura'=>$asignatura,'res'=>0,'est'=>$est));
    }
    
    private function numTotalToken($asig,$em,$t){
        $list = $em->getRepository('SISigueBundle:AsignaturaAlumno')->findBy(array('idAsignatura'=> $asig));
        if (!$list) return 0;
        $n = count($list);
        if ($n == 0) return 0;
        $num = 0;
        $max = -1;
        $mas = 0;
        $menos = 0;
        for($i = 0;$i<$n;$i++){
            $act = $list[$i]->getNum();
            $num = $num + $act;
            if($max < $act) {
                $max = $act;
            }
            if($act <= $t){
                $menos = $menos + 1; 
            }else{
                $mas = $mas + 1;
            }
        }
        /*if ($mas >0 or $menos>0){
            $mas = ($mas * 100)/$n;
            $menos = ($menos * 100)/$n;
        }*/
        return array('num' => $num,'max' => $max,'mas' => $mas, 'menos' => $menos);
    }
}
?>
