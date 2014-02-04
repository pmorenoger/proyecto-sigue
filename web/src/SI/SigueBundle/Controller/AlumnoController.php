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
        $total = 0;
        foreach ($asignaturas as $a){
            array_push($asig, $a->getIdAsignatura());
            $total = $total + $a->getNum();
        }
        
        $actividades = $em->getRepository('SISigueBundle:ActividadAsignatura')->findBy(array('idAlumno' => $alumno));
        return $this->render('SISigueBundle:Alumno:perfil.html.php',array('alumno' => $alumno,'asignaturas' => $asig, 'total' => $total,'actividades' => $actividades));
    }
    
    public function cambiarPasswordAction($id){
        //$peticion = $this->getRequest()->getSession();
        $request = Request::createFromGlobals();
        $em = $this->getDoctrine()->getEntityManager();
        
        //$id = $peticion->get('idalumno');//$request->request->get("id_alumno");
        $alumno = $em->getRepository('SISigueBundle:Alumnos')->find($id);
        $password = $request->request->get("verificar");
        
        $hash = self::hashSSHA($password);
        $alumno->setSalt($hash["salt"]);
        $alumno->setPassword($hash["encrypted"]);
        $em->persist($alumno);
        $em->flush();
        
        $asignaturas = $em->getRepository('SISigueBundle:AsignaturaAlumno')->findBy(array('idAlumno' => $id));
        $asig = array();
        $total = 0;
        foreach ($asignaturas as $a){
            array_push($asig, $a->getIdAsignatura());
            $total = $total + $a->getNum();
        }
        
        $actividades = $em->getRepository('SISigueBundle:ActividadAsignatura')->findBy(array('idAlumno' => $alumno));
        return $this->render('SISigueBundle:Alumno:perfil.html.php',array('alumno' => $alumno,'asignaturas' => $asig, 'total' => $total,'actividades' => $actividades,'res' => 1));
    }
    
    public function correoAdicionalAction($id){
        //$peticion = $this->getRequest()->getSession();
        $request = Request::createFromGlobals();
        $em = $this->getDoctrine()->getEntityManager();
        
        //$id = $peticion->get('idalumno');
        $alumno = $em->getRepository('SISigueBundle:Alumnos')->find($id);
        
        $correoAdicional = $request->request->get("correo_adicional");
        $alumno->setCorreoAdicional($correoAdicional);
        $em->persist($alumno);
        $em->flush();
        
        $asignaturas = $em->getRepository('SISigueBundle:AsignaturaAlumno')->findBy(array('idAlumno' => $id));
        $asig = array();
        $total = 0;
        foreach ($asignaturas as $a){
            array_push($asig, $a->getIdAsignatura());
            $total = $total + $a->getNum();
        }
        $actividades = $em->getRepository('SISigueBundle:ActividadAsignatura')->findBy(array('idAlumno' => $alumno));
        return $this->render('SISigueBundle:Alumno:perfil.html.php',array('alumno' => $alumno,'asignaturas' => $asig, 'total' => $total,'actividades' => $actividades,'res' =>2));
    
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
        $estAlumnos = NULL;
        $em = $this->getDoctrine()->getEntityManager();
        $alumno = $em->getRepository('SISigueBundle:Alumnos')->find($id);
        $asignatura = $em->getRepository('SISigueBundle:Asignaturas')->find($asig);
        if ($alumno and $asignatura){
            $a = $em->getRepository('SISigueBundle:AsignaturaAlumno')->findOneBy(array('idAsignatura'=> $asig,'idAlumno'=>$id));
            if($a){
                $array = self::numTotalToken($asig,$em,$a->getNum());
                $est = array('num' => $a->getNum(),'total' => $array['num'],'max' => $array['max'], 'mas' => $array['mas'], 'menos' => $array['menos']); 
                $estAlumnos = self::alumnosTokens($asig,$em);
            }
        }
        $opcion1 = self::prediccionNotaOpcion1($em,$id,$asig,0.25);
        $opcion2 = self::prediccionNotaOpcion2($em,$id,$asig,80,0);
        $opcion3 = self::prediccionNotaOpcion3($em,$id,$asig,6,7.5);
        $predicciones = array($opcion1,$opcion2,$opcion3);
        return $this->render('SISigueBundle:Alumno:registrar.html.php',array('alumno' => $alumno,'asignatura'=>$asignatura,'res'=>0,
                            'est'=>$est,'estAlumnos'=>$estAlumnos,'predicciones'=>$predicciones));
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
        return array('num' => $num,'max' => $max,'mas' => $mas, 'menos' => $menos);
    }
    
    private function alumnosTokens($asig,$em){
        //$list = $em->getRepository('SISigueBundle:AsignaturaAlumno')->findBy(array('idAsignatura'=>$asig));
        $query = $em->createQuery(  'SELECT T
                                    FROM SISigueBundle:AsignaturaAlumno T
                                    WHERE T.idAsignatura = :asig
                                    ORDER BY T.num ASC' )->setParameter('asig',$asig);
        $list = $query->getResult();
        if (!$list) return NULL;
        if (count($list) == 0) return NULL;
        return $list;
    }
    
    private function prediccionNotaOpcion1($em,$id,$asig,$peso){
        $query = $em->getRepository('SISigueBundle:AsignaturaAlumno')->findOneBy(array('idAsignatura'=> $asig,'idAlumno'=>$id));
        $num = $query->getNum();
        $valor = $num*$peso;
        if ($valor > 10) return 10;
        return $valor;
    }
    
    private function prediccionNotaOpcion2($em,$id,$asig,$tolerancia,$descartes){
        $queryMax = $em->createQuery(  'SELECT T
                                    FROM SISigueBundle:AsignaturaAlumno T
                                    WHERE T.idAsignatura = :asig
                                    ORDER BY T.num DESC'
                                    )->setParameter('asig',$asig);
        $list = $queryMax->getResult();
        if(!$list || count($list)<1) return 0;
        $max = $list[0]->getNum();
        if($descartes < count($list))
            $max = $list[$descartes]->getNum();
        $limite = $max*$tolerancia/100;
        $query = $em->getRepository('SISigueBundle:AsignaturaAlumno')->findOneBy(array('idAsignatura'=> $asig,'idAlumno'=>$id));
        $num = $query->getNum();
        if ($num >= $limite) return 10;
        return $num*10/16;
        
    }
    
    private function prediccionNotaOpcion3($em,$id,$asig,$n,$x){
        $queryNum = $em->createQuery(  'SELECT COUNT (T)
                                        FROM SISigueBundle:Codigos T
                                        WHERE T.id = :asig
                                        GROUP BY T.id'
                                        )->setParameter('asig',$asig);
        $numTokens = intval($queryNum->getResult()[0][1]);
        $queryN = $em->createQuery(  'SELECT COUNT (T)
                                    FROM SISigueBundle:AsignaturaAlumno T
                                    WHERE T.idAsignatura = :asig AND T.num >= :n'
                                    )->setParameter('asig',$asig)
                                     ->setParameter('n',$n);
        $numAl = intval($queryN->getResult()[0][1]);
        
        $numX = $numTokens/$numAl;
        $query = $em->getRepository('SISigueBundle:AsignaturaAlumno')->findOneBy(array('idAsignatura'=> $asig,'idAlumno'=>$id));
        $num = $query->getNum();
        if ($num == $numX) return $x;
        return $num*$x/$numX;
    }
    
    private function hashSSHA($password) {
        $salt = sha1(rand());
        $salt2 = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt2, true) . $salt2);
        $hash = array("salt" => $salt2, "encrypted" => $encrypted);
        return $hash;     
   }
}
?>
