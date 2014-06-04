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
        //$peticion = $this->getRequest()->getSession();
        $peticion = $this->container->get('session');
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
        
        $cod = self::getCodigoCifrado($alumno);
        
        $asig = self::getAsignaturas($em,$id);
        
        $actividades = self::getActividades($em,$alumno);
        
        return $this->render('SISigueBundle:Alumno:perfil.html.php',array('alumno' => $alumno,'asignaturas' => $asig,'actividades' => $actividades,'cod' => $cod));
    }
    
    public function cambiarPasswordAction($id){
        //$peticion = $this->getRequest()->getSession();
        $request = Request::createFromGlobals();
        $em = $this->getDoctrine()->getEntityManager();
        
        //$id = $peticion->get('idalumno');//$request->request->get("id_alumno");
        $alumno = $em->getRepository('SISigueBundle:Alumnos')->find($id);
        $password = $request->request->get("verificar");
        
        $oldCod = $alumno->getCodigo_id(); 
        $query = $em->createQuery(  "SELECT T
                                    FROM SISigueBundle:Codigos T
                                    WHERE T.codigo = :cod" )->setParameter('cod',$oldCod);
        $list = $query->getResult();
        if(!is_null($list) && count($list)== 1){
            $codigo = $list[0];
            $codigo->setCodigo($alumno->getCorreo()."#&".$password);
            $em->persist($codigo);
        }
        
        $hash = self::hashSSHA($password);
        $alumno->setSalt($hash["salt"]);
        $alumno->setPassword($hash["encrypted"]);
        $alumno->setCodigo_id($alumno->getCorreo()."#&".$password);
        $em->persist($alumno);
        $em->flush();
        
        $asig = self::getAsignaturas($em,$id);
        
        $actividades = self::getActividades($em,$alumno);
        
        $cod = self::getCodigoCifrado($alumno);
        
        return $this->render('SISigueBundle:Alumno:perfil.html.php',array('alumno' => $alumno,'asignaturas' => $asig,'actividades' => $actividades,'cod' => $cod,'res' => 1));
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
        
        $asig = self::getAsignaturas($em,$id);
        
        $actividades = self::getActividades($em,$alumno);
        
        $cod = self::getCodigoCifrado($alumno);
        
        return $this->render('SISigueBundle:Alumno:perfil.html.php',array('alumno' => $alumno,'asignaturas' => $asig,'actividades' => $actividades,'cod' => $cod,'res' =>2));
    }
    
    public function registrarAction($id,$asig){
        //obtenenos el alumno y la asignatura con que realziaremos las diferentes opciones
        $em = $this->getDoctrine()->getEntityManager();
        $alumno = $em->getRepository('SISigueBundle:Alumnos')->find($id);
        $asignatura = $em->getRepository('SISigueBundle:Asignaturas')->find($asig);
        
        return $this->render('SISigueBundle:Alumno:registrar.html.php',array('alumno' => $alumno,'asignatura'=>$asignatura,'res'=>0));
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
                $res = 4;
            }else{
                $res = 3;
            }   
        }else{
            $res = 3;
        }
        
        $as = self::getAsignaturas($em,$id);
        
        $actividades = self::getActividades($em,$alumno);
        
        $cod = self::getCodigoCifrado($alumno);
        
        return $this->render('SISigueBundle:Alumno:perfil.html.php',array('alumno' => $alumno,'asignaturas' => $as,'actividades' => $actividades,'cod' => $cod,'res' => $res,'selected'=>$asig));
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
        if ($asignatura->getIdeval() != null){
            $predicciones = self::getPrediccion($em,$id,$asig,$asignatura->getIdeval(),$asignatura->getParameval());
        }else{
            $predicciones = 0;
        }
        
        $as = self::getAsignaturas($em,$id);
        
        $actividades = self::getActividades($em,$alumno);
        
        $cod = self::getCodigoCifrado($alumno);
        
        return $this->render('SISigueBundle:Alumno:perfil.html.php',array('alumno' => $alumno,'asignaturas' => $as,'actividades' => $actividades,'cod' => $cod,
                                                                        'est'=>$est,'estAlumnos'=>$estAlumnos,'predicciones'=>$predicciones,'selected'=>$asig));    
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
    
    private function getPrediccion($em,$id,$asig,$metodoEval,$param){
        $idEval = $metodoEval->getIdeval();
        $p = array();
        $prediccion = 0;
        switch ($idEval){
            case 1: $p = self::getParametrosOpcion1($param);
                    $prediccion = self::prediccionNotaOpcion1($em,$id,$asig,$p[0]);
                break;
            case 2: $p = self::getParametrosOpcion2($param);
                    $prediccion = self::prediccionNotaOpcion2($em,$id,$asig,$p[0],$p[1]);
                break;
            case 3: $p = self::getParametrosOpcion3($param);
                    $prediccion = self::prediccionNotaOpcion3($em,$id,$asig,$p[0],$p[1]);
                break;
        }
        return number_format($prediccion,2);
    }
    
    private function getParametrosOpcion1($param){
        parse_str($param,$output);
        $var = $output['valor_absoluto'];
        return array (floatval($var));
    }
    
    private function getParametrosOpcion2($param){
        $vars = explode("##",$param);
        parse_str($vars[0],$out1);
        parse_str($vars[1],$out2);
        $p = array();
        $p[0] = floatval($out1['margen_tolerancia']);  
        $p[1] = floatval($out2['num_notas_descartar']);
        return $p;
    }
    
    private function getParametrosOpcion3($param){
        $vars = explode("##",$param);
        parse_str($vars[0],$out1);
        parse_str($vars[1],$out2);
        $p = array();
        $p[0] = floatval($out1['nota_referencia']);  
        $p[1] = floatval($out2['minimo_tokens']);
        return $p;
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
        $numTokens = 0;
        if(count($queryNum->getResult())>0){
            $numTokens = intval($queryNum->getResult()[0][1]);
        }else{
            return 0;
        }
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
   
   private function getAsignaturas($em,$id){
       $asignaturas = $em->getRepository('SISigueBundle:AsignaturaAlumno')->findBy(array('idAlumno' => $id));
       $as = array();
       foreach ($asignaturas as $a){
           array_push($as, $a->getIdAsignatura());
       }
       return $as;
   }
   
   private function getActividades($em,$alumno){
       $actividades = $em->getRepository('SISigueBundle:ActividadAsignatura')->findBy(array('idAlumno' => $alumno));
       return $actividades;
   }
   
   private function getCodigoCifrado($alumno){
        $key = "sigue";
        $string = $alumno->getCodigo_id();
        $result = ""; 
        for($i=0; $i<strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key))-1, 1);
            $char = chr(ord($char)+ord($keychar));
            $result .= $char;
        }
        return base64_encode($result);
    }
}
?>
