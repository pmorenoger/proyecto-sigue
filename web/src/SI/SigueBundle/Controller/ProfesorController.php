<?php

namespace SI\SigueBundle\Controller;



use SI\SigueBundle\Entity\Alumnos;
use SI\SigueBundle\Entity\Codigos;
use SI\SigueBundle\Entity\Asignaturas;
use SI\SigueBundle\Entity\ActividadAsignatura;
use SI\SigueBundle\Entity\ProfesorAsignatura;
use SI\SigueBundle\Entity\AsignaturaAlumno;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Response;
require_once ('../vendor/PHPqrcode/phpqrcode.php');
class ProfesorController extends Controller
    {
        public function indexAction($exito)
        {     
            $asig = self::getAsignaturas();
           
            $peticion = $this->container->get('session');
            $p = $peticion->get('pAl');
            $profesor = self::getProfesor();
                 if($profesor === "session_lost"){
                    $session = $this->container->get('session');
                    $session->remove('idalumno');
                    $session->remove('idprofesor');                   
                   return $this->redirect($this->generateUrl('si_sigue_homepage'));
                }
            //TODO Redigir si no hay login.
            $em = $this->getDoctrine()->getEntityManager();
            if ($profesor->getCodigo() === NULL){
                $cod = $profesor->getCorreo()."#&".$p;
                $profesor->setCodigo($cod);
                $em->persist($profesor);
                $em->flush();
                //guardamos el código generado en la BBDD
                $codigo = new Codigos();
                $codigo->setCodigo($profesor->getCodigo());
                $em->persist($codigo);
                $em->flush();
            }
            $miCodigo = self::getCodigoEncriptado($profesor);
           
           if(!is_array($exito)){                           
               return $this->render('SISigueBundle:Profesor:index.html.php',array("exito" => $exito,'asignaturas' =>$asig,"cod"=>$miCodigo));
           }else{
               $asig2 = array("asignaturas" => $asig);
               if(! array_key_exists("alumnos",$exito)){
                  $exito = array_merge($exito, array("alumnos" => null)) ;
               }
               $exito = array_merge($exito,$asig2);
               //var_dump($exito)
               return $this->render('SISigueBundle:Profesor:index.html.php',$exito);
           }              
        }
        public function subir_alumnoAction(){
            self::hayLogin();
            $kernel = $this->get('kernel');
            $dir_abs = self::getDireccionAbsoluta();
            require_once $dir_abs . '/vendor/Excel/lib/src/Classes/PHPExcel.php';
            
             $request = Request::createFromGlobals();
            /*PARA SUBIR UNA LISTA EXCEL DE ALUMNOS, HAY QUE:
             * 1º PROCESAR EL FICHERO \/
             * 2º GUARDAR CADA FILA EN LA TABLA ALUMNOS (con la entity Alumno) \/
             * 3º GUARDAR EL COMBO PROFESOR-ASIGNATURA \/
             * 
             */
            
             $exito = "none";  
             if( $kernel->getEnvironment() === "dev" ){
                 /*Si quereis que funcione en vuestro desarrollo localhost cambiad esta ruta.*/                                               
                 //DIEGO:
                 $uploaddir = self::getDireccionAbsoluta()."/web/archivos/";
                $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
               
             }else{
                $uploaddir = '/var/www/Symfony/web/archivos/';
                $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
               
                }               
                if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                   $exito ="true";
                } else {
                   $exito ="false";
                }
             /*Se ha cargado y vamos a procesarlo para pintarlo*/
                    $inputFileName = $uploadfile;
                                             
                    /**  Identify the type of $inputFileName  **/
                    $inputFileType = \PHPExcel_IOFactory::identify($inputFileName);
                    /**  Create a new Reader of the type that has been identified  **/
                    $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
                    
                    $objPHPExcel = $objReader->load($inputFileName);

                    $objWorksheet = $objPHPExcel->getActiveSheet();
                    /*Recogemos todos los datos del formulario de creación de asignaturas.*/
                    $nombre_asignatura = $request->request->get('nombre_asignatura', 'default');

                    $curso = $request->request->get('curso', '1');
                    $grupo = $request->request->get('grupo', 'A');

                    $session = $this->get("session");
                    $idprofesor =$session->get('idprofesor');
                    /*Generamos la asignatura donde deben ser insertados los alumnos*/
                    $em = $this->getDoctrine()->getManager();
                    $asignatura = new Asignaturas();
                    $asignatura->setCurso($curso);
                    $asignatura->setGrupo($grupo);
                    $asignatura->setNombre($nombre_asignatura);
                    $em->persist($asignatura);
                    $em->flush();
                    
                    foreach ($objWorksheet->getRowIterator() as $row) {                        

                        $cellIterator = $row->getCellIterator();
                        $cellIterator->setIterateOnlyExistingCells(true); // This loops all cells iterated.                       
                        self::subir_alumno($cellIterator, $asignatura);                    
                    }
                   $profesor = $em->getRepository('SISigueBundle:Profesor')->find($idprofesor);
                   $profesor_asignatura = new ProfesorAsignatura();
                   $profesor_asignatura->setIdAsignatura($asignatura->getId());
                   $profesor_asignatura->setIdProfesor($profesor);
                   $em->persist($profesor_asignatura);
                   $em->flush(); 
                   return $this->indexAction("true");
                
        }
        
        
        public function subir_alumno($fila, $asignatura){
            $em = $this->getDoctrine()->getManager();
            $alumno = new Alumnos();
            foreach($fila as $celda){               
                //echo "FILAS " . var_dump($celda->getRow());
                if($celda->getRow() > 1){
                    //echo "COLMUNAS " . var_dump($celda->getColumn());
                    switch ($celda->getColumn()){
                        case "A":
                                $alumno->setNombre($celda->getValue());
                                break;
                        case "B":
                                $alumno->setApellidos($celda->getValue());
                                break;
                        case "C":
                                $alumno->setIdalumno($celda->getValue());
                                break;                        
                        case "E":
                                $alumno->setCorreo($celda->getValue());
                                break;    
                    }
                }else{
                    return;
                }
                $existe_alumno = $em->getRepository('SISigueBundle:Alumnos')->findOneByCorreo($alumno->getCorreo());
                if(!$existe_alumno){
                    $pass_provisional = explode("@",$alumno->getCorreo());
                    $pass_provisional[0] = $pass_provisional[0].rand(0,99);
                    
                    $hash = self::hashSSHA($pass_provisional[0]);
                    $encrypted_password = $hash["encrypted"];
                    $salt = $hash["salt"];
                    $alumno->setSalt($salt);
                    $alumno->setPassword($encrypted_password);
                    
                    //ENVIAR POR EMAIL AL ALUMNO//
                   $message = \Swift_Message::newInstance('ssl://smtp.gmail.com', 465)
                    ->setSubject('Alta Usuario SIGUE')
                    ->setFrom('admin@sigue.com')
                    ->setTo($alumno->getCorreo())
                    ->setBody('<h3> ¡Bienvenido! </h3> <p>Ha sido añadido al sistema SIGUE de la ucm.<br /> 
            <p>Su usuario es esta dirección de correo y su password es: '. $pass_provisional[0].'</p>
                <a href="ssii2013.e-ucm.es" title="Ir a Sigue">SIGUE </a> ', 'text/html' );
                    $this->get('mailer')->send($message);
                        
                }                
           }
           //var_dump($alumno);
           //var_dump($existe_alumno);
           /*Solo lo añado a la tabla alumnos si no existe en esa tabla*/
           if($existe_alumno){                              
                $alumno = $existe_alumno[0];
           }
           $em->persist($alumno);
           /*A la asignatura nueva se añade siempre*/
           $asignatura_alumno = new AsignaturaAlumno();
           $asignatura_alumno->setIdAlumno($alumno);
           $asignatura_alumno->setIdAsignatura($asignatura);
           $asignatura_alumno->setNum(0);
           $em->persist($asignatura_alumno);
        }
        
        public function descargar_pdfAction($pdf){
            self::hayLogin();
             $path = self::getDireccionAbsoluta() ."/web/archivos/pdfs/". $pdf;
            $content = file_get_contents($path);

            $response = new Response();

            $response->headers->set('Content-Type', 'file/pdf');
            $response->headers->set('Content-Disposition', 'attachment;filename="'.$pdf);

            $response->setContent($content);
            /*
            $response = new Response();
            $d = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $pdf);

            $response->headers->set('Content-Disposition', $d);
             * */
            
            return $response;
        }
        
         public function add_alumnoAction($id_asignatura){
             $em = $this->getDoctrine()->getManager();              
             $asignaturas = self::getAsignaturas();
             $asignatura = $em->getRepository('SISigueBundle:Asignaturas')->findOneById($id_asignatura);
             
             $array =  array();
             $array["asignaturas"] = $asignaturas;
             $array["asignatura"] = $asignatura;
             $array["exito"] = "";            
           
            return $this->render('SISigueBundle:Profesor:add_alumno.html.php', $array);
            
            
        }
        
        public function add_alumnoGuardarAction($id_asignatura){
            
            
            $em = $this->getDoctrine()->getManager();              
             $asignaturas = self::getAsignaturas();            
             $array =  array();
             $array["asignaturas"] = $asignaturas;
              $asignatura = $em->getRepository('SISigueBundle:Asignaturas')->findOneById($id_asignatura);
             $array["asignatura"] = $asignatura;
              $request = Request::createFromGlobals();
              $alumno = new Alumnos();
              
              $nombre =  $request->request->get('nombre', 'fallo');
              $apellidos = $request->request->get('apellidos', 'fallo');
              $correo = $request->request->get('correo', 'fallo');
              
              if($nombre != "fallo" && $apellidos != "fallo" && $correo != "fallo"){
              $alumno->setNombre($nombre);
              $alumno->setApellidos($apellidos);
              $alumno->setCorreo($correo);
              $existe_alumno = $em->getRepository('SISigueBundle:Alumnos')->findOneByCorreo($alumno->getCorreo());
             
              if(!$existe_alumno){
                    $pass_provisional = explode("@",$alumno->getCorreo());
                    $pass_provisional[0] = $pass_provisional[0].rand(0,99);
                    
                    $hash = self::hashSSHA($pass_provisional[0]);
                    $encrypted_password = $hash["encrypted"];
                    $salt = $hash["salt"];
                    $alumno->setSalt($salt);
                    $alumno->setPassword($encrypted_password);
                    
                    //ENVIAR POR EMAIL AL ALUMNO//
                   $message = \Swift_Message::newInstance('ssl://smtp.gmail.com', 465)
                    ->setSubject('Alta Usuario SIGUE')
                    ->setFrom('admin@sigue.com')
                    ->setTo($alumno->getCorreo())
                    ->setBody('<h3> ¡Bienvenido! </h3> <p>Ha sido añadido al sistema SIGUE de la ucm.<br /> 
            <p>Su usuario es esta dirección de correo y su password es: '. $pass_provisional[0].'</p>
                <a href="ssii2013.e-ucm.es" title="Ir a Sigue">SIGUE </a> ', 'text/html' );
                    $this->get('mailer')->send($message);
                        
                }                
                /*
                var_dump($alumno);
                var_dump($existe_alumno);die();*/
                /*Solo lo añado a la tabla alumnos si no existe en esa tabla*/
                if($existe_alumno){                              
                     $alumno = $existe_alumno;
                }
                $em->persist($alumno);
                $em->flush();
                //Controlo que el alumno no esté ya en la asignatura
                $asignatura_alumno = $em->getRepository('SISigueBundle:AsignaturaAlumno')->findOneById_alumno($alumno->getIdalumno());
                
                if(!$asignatura_alumno){
                
                /*A la asignatura nueva se añade siempre*/
                $asignatura_alumno = new AsignaturaAlumno();
                $asignatura_alumno->setIdAlumno($alumno);
                $asignatura_alumno->setIdAsignatura($asignatura);
                $asignatura_alumno->setNum(0);
                $em->persist($asignatura_alumno);
                $array["exito"] = "true";
                //Así como todas las actividades de esa asignatura para ese alumno:
                $query = $em->createQuery(  "SELECT T
                                    FROM SISigueBundle:ActividadAsignatura T
                                    WHERE T.idAsignatura = :id
                                    GROUP BY T.nombre
                                    " )->setParameter('id',$asignatura->getId());
                $actividades = $query->getResult();
                
                
               
                foreach($actividades as $actividad){
                    $nueva = new ActividadAsignatura();
                    $nueva->setNombre($actividad->getNombre());
                    $nueva->setPeso($actividad->getPeso());
                    $nueva->setNota(0);
                    $nueva->setDescripcion($actividad->getDescripcion());
                    $nueva->setObservaciones("");
                    $nueva->setFechaCreacion($actividad->getFechaCreacion());
                    $nueva->setFechaLimite($actividad->getFechaLimite());                  
                    $nueva->setIdAlumno($alumno);
                    $nueva->setIdAsignatura($asignatura);
                    $em->persist($nueva);
                }
                
                
                 $em->flush();
                }else{
                    $array["exito"] = "false2";         
                }
           } else{
             
             
              $array["exito"] = "false";            
              
              }
            return $this->render('SISigueBundle:Profesor:add_alumno.html.php', $array);
        }
        
        public function cambiar_passwordAction(){
             $em = $this->getDoctrine()->getManager();              
             $asignaturas = self::getAsignaturas();            
             $array =  array();
             $array["asignaturas"] = $asignaturas;
             $array["asignatura"] = null;
             $array["exito"] = "";            
           
            return $this->render('SISigueBundle:Profesor:cambiar_password.html.php', $array);
            
            
        }
        
        public function cambiar_passwordGuardarAction(){
            
             $em = $this->getDoctrine()->getManager();              
             $asignaturas = self::getAsignaturas();            
             $array =  array();
             $array["asignaturas"] = $asignaturas;
             $array["asignatura"] = null;
                        
              $request = Request::createFromGlobals();
        $em = $this->getDoctrine()->getEntityManager();
        
        //$id = $peticion->get('idalumno');//$request->request->get("id_alumno");
       $profesor = self::getProfesor();
        $password = $request->request->get("password");
        
        $oldCod = $profesor->getCodigo(); 
        $query = $em->createQuery(  "SELECT T
                                    FROM SISigueBundle:Codigos T
                                    WHERE T.codigo = :cod" )->setParameter('cod',$oldCod);
        $list = $query->getResult();
        if(!is_null($list) && count($list)== 1){
            $codigo = $list[0];
            $codigo->setCodigo($profesor->getCorreo()."#&".$password);
            $em->persist($codigo);
            $profesor->setCodigo($codigo->getCodigo());
        }
        
        $hash = self::hashSSHA($password);
        $profesor->setSalt($hash["salt"]);
        $profesor->setPassword($hash["encrypted"]);        
        $em->persist($profesor);
        $em->flush();
        $array["exito"] = "true"; 
                                 
             
        return $this->render('SISigueBundle:Profesor:cambiar_password.html.php', $array);
            
        }
        public function activar_appAction(){
             self::hayLogin();
             $codigo =  self::getCodigoEncriptadop();
             
             //var_dump($codigo);die();
            $key = "sigue";
            $result =$codigo;
            $res = "";
            $string = base64_decode($result);
            for($i=0; $i<strlen($string); $i++) {
                $char = substr($string, $i, 1);
                $keychar = substr($key, ($i % strlen($key))-1, 1);
                $char = chr(ord($char)-ord($keychar));
                $res .= $char;
            }
            $data = $res;
            //tratamos el código QR
            $nombre = explode("@", $data);
            $nombre = $nombre[0];
            $nombre = 'qr'.$nombre.'.png';
            $dir =  '../web/img/'.$nombre;

            \QRcode::png($data, $dir,QR_ECLEVEL_H,6);
    
             
             
             $em = $this->getDoctrine()->getManager();              
             $asignaturas = self::getAsignaturas();            
             $array =  array();
             $array["asignaturas"] = $asignaturas;
             $array["asignatura"] = null;
             $array["exito"] = "";
             $dir_real = str_replace("..","",$dir);
             $array["dir"] = $dir_real;
           
            return $this->render('SISigueBundle:Profesor:activar_app.html.php', $array);
        }
        
        
        
        
        public function cambiar_metodoAction(){
            self::hayLogin();
            /*Aqui debo asignar el método de evaluación*/            
            /*Dependiendo del metodo, debo guardar los parametros*/
            $request = Request::createFromGlobals();
            $metodo = $request->request->get('metodo');
            $metodo_int = intval($metodo);
            $id_asignatura = $request->request->get('id_asignatura');
            $params = "";
            $cambio = false;
            if($metodo_int === 1){
                $param1 = $request->request->get('valor_absoluto');
                $params = $params . "valor_absoluto=".$param1;
                $param3 = $request->request->get('peso_nota_final1');
                $params = $params . "##peso_nota_final=".$param3;
                $cambio = true;
            }else if($metodo_int === 2){
                $param1 = $request->request->get('margen_tolerancia');
                $params = $params . "margen_tolerancia=".$param1;
                $param2 = $request->request->get('num_notas_descartar');
                $params = $params . "##num_notas_descartar=".$param2;
                $param3 = $request->request->get('peso_nota_final');
                $params = $params . "##peso_nota_final=".$param3;
                $cambio = true;
            }else  if($metodo_int === 3){
                $param1 = $request->request->get('nota_referencia');
                $params = $params . "nota_referencia=".$param1;
                $param2 = $request->request->get('minimo_tokens');
                $params = $params . "##minimo_tokens=".$param2;
                $param3 = $request->request->get('peso_nota_final2');
                $params = $params . "##peso_nota_final=".$param3;
                $cambio = true;
            }    
            if($cambio){
             $em = $this->getDoctrine()->getManager();
             $asignatura = $em->getRepository('SISigueBundle:Asignaturas')->find($id_asignatura);
             $metodo_eval = $em->getRepository('SISigueBundle:MetodosEvaluacion')->findOneByIdeval($metodo_int);
             $asignatura->setIdEval($metodo_eval);
             $asignatura->setParameval($params);
             $em->persist($asignatura);
             $em->flush();
            }
            //var_dump($metodo);die();
            
            return $this->metodoAction($id_asignatura, "true");
        }
        
        
        
        
        public function generar_qrAction(){
               self::hayLogin();
                $request = Request::createFromGlobals();
                $cantidad = $request->request->get('cantidad');
                $id_asignatura = $request->request->get('id_asignatura');
                
                $lista_codigos = array();
                $codigo = new Codigos();
                $em = $this->getDoctrine()->getManager();
                $asignatura = $em->getRepository('SISigueBundle:Asignaturas')->find($id_asignatura);
                /*
                var_dump($asignatura);
                die();
                 * */
                
                for($i = 0;$i < $cantidad; $i++){
                    $cuerpo_codigo = $unique_key = substr(md5(rand(0, 1000000)), 0, 15 );
                    // var_dump($cuerpo_codigo);                  
                    $codigo = $em->getRepository('SISigueBundle:Codigos')->findByCodigo( $cuerpo_codigo);
                    if(!$codigo){
                        $codigo = new Codigos();
                        $codigo->setCodigo($cuerpo_codigo);
                        $codigo->setId($asignatura);
                        $date_time_zone = new \DateTimeZone("Europe/Madrid");
                        $date_time = new \DateTime("now",$date_time_zone);
                        $codigo->setFechaCreacion($date_time);
                        $em->persist($codigo);
                        array_push($lista_codigos,$codigo);
                        
                    }else{
                        /*Ese codigo queda descartado*/
                        $i--;
                    }   
                }
                $em->flush();
                
                /*Aquí debo manejar los códigos ($lista_codigos) para mandarlos por email en pdf o algo.*/                               
                /*1º Crear los códigos QR a partir de los códigos normales*/
                $rutas_codigos = self::crearImgCodigos($lista_codigos);
                /*2º Crear el pdf a partir de todas la imágenes generadas*/
                $ruta_pdf = self::crearPdfCodigos($rutas_codigos);
                /*3º Enviar por email*/
          
               
                
                return $this->generar_tokensAction($id_asignatura,$ruta_pdf);
                
            }
               
        public function statsAction($id_asignatura){
            /*Aquí se calculan las estadísticas de la parte del profesor. 
             * 1.- Totales generados / redimidos
             * 2.- Códigos de cada alumno
             * 3.- Registrados por fecha
             *          --Falta--
             * 4.- Registrados por plataforma
             * 
             *     Pendientes
             * 5.- Predicciones de nota
             * 6.- Cálculo de los SS, AP, NT y SB
             * 
             */
            self::hayLogin();
            $em = $this->getDoctrine()->getManager();
            $asignatura = $em->getRepository('SISigueBundle:Asignaturas')->find($id_asignatura);
            
            $query = $em->createQuery(
                'SELECT count(cod)
                FROM SISigueBundle:Codigos cod
                WHERE cod.id = :id
                '
                )->setParameter('id', $id_asignatura);

            $totales = $query->getResult();
              $totales = intval($totales[0][1]);
            
            
            $query = $em->createQuery(
                'SELECT count(cod)
                FROM SISigueBundle:Codigos cod
                WHERE cod.id = :id and cod.fechaAlta is not NULL
                '
                )->setParameter('id', $id_asignatura);

            $redimidos = $query->getResult();
            $redimidos = intval($redimidos[0][1]);
            //Codigo por cada alumno//           
           $alumnos = $em->getRepository('SISigueBundle:AsignaturaAlumno')->findByIdAsignatura($asignatura);
           $exito = "stats_".$asignatura->getId();
          
           //Codigos por fechas
            $query = $em->createQuery(
                'SELECT cod
                FROM SISigueBundle:Codigos cod
                WHERE cod.id = :id and cod.fechaAlta is not NULL
                '
                )->setParameter('id', $id_asignatura);

            $fechas = $query->getResult();
            
            $fechas_veces = array();          
            if(!empty($fechas)){           
                $str_fecha = $fechas[1]->getFechaAlta()->format("Y-m-d");
           
                $veces = 0;
                foreach($fechas as $fecha){
                   if($fecha->getFechaAlta()->format("Y-m-d") === $str_fecha){        
                       $veces++;
                   }else{
                       array_push($fechas_veces, array("fecha"=>$str_fecha, "veces" => $veces));
                       $veces = 1;
                       $str_fecha = $fecha->getFechaAlta()->format("Y-m-d");
          
                   }

               }
                array_push($fechas_veces, array("fecha"=>$str_fecha, "veces" => $veces));
            }
           
            
           $asignaturas = self::getAsignaturas();
           $array = ["asignatura" => $asignatura, "exito" => $exito, "alumnos" =>$alumnos, "totales" =>$totales, "redimidos" => $redimidos, "fechas_veces" => $fechas_veces, "asignaturas" => $asignaturas ];           
           //return   $this->indexAction($array);
           return $this->render('SISigueBundle:Profesor:stats.html.php',$array);
        }
        
        public function notificarAction($id_asignatura){
            self::hayLogin();
            $em = $this->getDoctrine()->getManager();
            $asignatura = $em->getRepository('SISigueBundle:Asignaturas')->findOneById($id_asignatura);
            $query = $em->createQuery(
                'SELECT IDENTITY (a.idAlumno)
                FROM SISigueBundle:AsignaturaAlumno a
                WHERE a.idAsignatura = :id
                '
                )->setParameter('id', $id_asignatura);
                                               
            $query2 = $em->createQuery(
                'SELECT g
                FROM SISigueBundle:Alumnos g
                WHERE g.idalumno IN ('.$query->getDQL().') 
                '
                )->setParameter('id', $id_asignatura);

            $alumnos = $query2->getResult();
            //var_dump($alumnos);die();
            $messageText = "Ha habido cambios en las notas de ".$asignatura->getNombre();
            $resultado = self::sendMessageToPhone($asignatura->getNombre(), $messageText, $alumnos);
            self::enviar_correo($id_asignatura);          
            return self::calificarAction($id_asignatura, true);
        }
        
        private function enviar_correo($id_asignatura){
             $em = $this->getDoctrine()->getManager();
            $asignatura = $em->getRepository('SISigueBundle:Asignaturas')->findOneById($id_asignatura);
           $alumnos = $em->getRepository('SISigueBundle:AsignaturaAlumno')->findByIdAsignatura($asignatura);
           foreach($alumnos as $alumno){
               $message = \Swift_Message::newInstance('ssl://smtp.gmail.com', 465)
                    ->setSubject('Cambios en calificaciones')
                    ->setFrom('admin@sigue.com')
                    ->setTo($alumno->getIdAlumno()->getCorreo())
                    ->setBody('<h3> '.$asignatura->getNombre(). '</h3> <p>Ha habido cambios en las calificaciones de la asignatura</p>
                <a href="ssii2013.e-ucm.es" title="Ir a Sigue">SIGUE </a> ', 'text/html' );
                    $this->get('mailer')->send($message);
               
           }
           
                                               
        }
        
        public function generar_tokensAction($id_asignatura, $ruta_pdf = ""){
            self::hayLogin();
              $em = $this->getDoctrine()->getManager();
              $asignatura = $em->getRepository("SISigueBundle:Asignaturas")->findOneById($id_asignatura);
             $asignaturas = self::getAsignaturas();
             //var_dump($asignaturas);die();
             $array =  array();
             $array["asignaturas"] = $asignaturas;
             $array["asignatura"] = $asignatura;
             $array["exito"] = "";
             if($ruta_pdf != ""){
                $array["exito"] = "true2";
                $array["ruta_pdf"] = $ruta_pdf;                 
             }
             
            return $this->render('SISigueBundle:Profesor:codigos.html.php', $array);
        }
        
        public function metodoAction($id_asignatura, $exito = ""){
             self::hayLogin();
              $em = $this->getDoctrine()->getManager();
              $asignatura = $em->getRepository("SISigueBundle:Asignaturas")->findOneById($id_asignatura);
             $asignaturas = self::getAsignaturas();
             //var_dump($asignaturas);die();
             $array =  array();
             $array["asignaturas"] = $asignaturas;
             $array["asignatura"] = $asignatura;
             $array["exito"] = "";
             if($exito != ""){
                $array["exito"] = $exito;                      
             }
            return $this->render('SISigueBundle:Profesor:metodos.html.php', $array);
        }
        
        public function calificarAction($id_asignatura, $exito = false){            
            self::hayLogin();
            $array = self::listado_alumnos_actividad_asignatura($id_asignatura, $exito);
            $em = $this->getDoctrine()->getManager();
            $asignatura = $em->getRepository("SISigueBundle:Asignaturas")->findOneById($id_asignatura);
            $asignaturas = self::getAsignaturas();
            $array["asignaturas"] = $asignaturas;
            $array["asignatura"] = $asignatura;
            $array["subopciones"] = "true";
            return $this->render('SISigueBundle:Profesor:calificar.html.php', $array);
        }
        
         public function calificar_actividadAction($id_asignatura, $id_alumno){            
              $em = $this->getDoctrine()->getManager();
              $asignatura = $em->getRepository("SISigueBundle:Asignaturas")->findOneById($id_asignatura);
              $alumno = $em->getRepository("SISigueBundle:Alumnos")->findOneByIdalumno($id_alumno);
             self::hayLogin();
               $query = $em->CreateQuery(
                        'SELECT p
                         FROM SISigueBundle:ActividadAsignatura p
                            WHERE p.idAsignatura = :idAsignatura  
                            AND p.idAlumno = :idAlumno
                            '
                        )->SetParameters(array('idAsignatura'=>$id_asignatura, 'idAlumno'=>$alumno));
                $actividades = $query->getResult();              
                /*
                $query2 = $em->CreateQuery(
                            'SELECT cod
                            FROM SISigueBundle:AsignaturaAlumno cod
                            where cod.idAsignatura = :idAsignatura
                            AND cod.idAlumno = :idAlumno
                            ')->SetParameters(array('idAsignatura'=>$id_asignatura, 'idAlumno'=>$alumno));
                $codigos = $query2->getResult();
                 */
              $resultado["asignatura"] = $asignatura;
              $resultado["alumno"] = $alumno;
              $resultado["actividades"] = $actividades;
              $resultado["asignaturas"] = self::getAsignaturas();
             
           return $this->render('SISigueBundle:Profesor:calificar_actividad.html.php',$resultado);  
         }
        
         
           public function calificar_actividad_guardarAction($id_asignatura, $id_alumno){    
               self::hayLogin();
               /*Aquí hay que guardar el formulario con las notas. El profesor habrá rellenado el 
                * formulario, y hay que recoger cada nota y guardarlo debidamente.
                */
               $em = $this->getDoctrine()->getManager();
               $query = $em->CreateQuery(
                        'SELECT p
                         FROM SISigueBundle:ActividadAsignatura p
                            WHERE p.idAsignatura = :idAsignatura  
                            AND p.idAlumno = :idAlumno
                            '
                        )->SetParameters(array('idAsignatura'=>$id_asignatura, 'idAlumno'=>$id_alumno));
               $actividades = $query->getResult();
              
               $request = Request::createFromGlobals();
               $datos = $request->request->all();
               $claves = array_keys($request->request->all());
               foreach($claves as $clave){                                                         
                  if(substr($clave, 0,5) == "nota_"){
                      $id_actividad = substr($clave,5);
                      $id_actividad2 = $id_actividad;
                      $id_actividad = str_replace("%/%", " ", $id_actividad);
                      //var_dump($id_actividad);
                      $encontrado = false;
                      $i = 0;
                      $observaciones="";
                      while(!$encontrado && $i<  count($actividades) ){
                          //var_dump($id_actividad . $actividades[$i]->getNombre());
                          if($id_actividad === $actividades[$i]->getNombre()){
                              $valor_final = str_replace(",", ".", $datos[$clave]);                                   
                              $actividades[$i]->setNota($valor_final);
                              $observaciones = $request->request->get("obs_".$id_actividad2);                                                           
                              $actividades[$i]->setObservaciones($observaciones);
                              $em->persist($actividades[$i]);
                              unset($actividades[$i]);
                              $actividades = array_values($actividades);
                              $encontrado = true;
                          }
                         $i++; 
                      }                     
                   }                   
                }
               
               $em->flush();
              return $this->calificarAction($id_asignatura);
           }
        
        public function guardar_calificacionesAction($id_asignatura){
                self::hayLogin();
                $request = Request::createFromGlobals();
                $notas = $request->request->all();
                $claves = array_keys($notas);
                $em = $this->getDoctrine()->getManager();
                $repository = $this->getDoctrine()->getRepository('SISigueBundle:ActividadAsignatura');
                //var_dump($claves);
                //die();
                foreach ($claves as $clave){
                    //Tengo toda la info para saber cual es en la propia clave,
                    // y el dato en el valor del array.
                    $identificadores = explode("_", $clave);
                     //var_dump($identificadores);
                    $id_asignatura2 = $identificadores[1];
                    $id_alumno = $identificadores[2];
                    $nombre_actividad = $identificadores[3];
                    $nombre_actividad = str_replace("%/%"," ",$nombre_actividad);
                     //var_dump($nombre_actividad);
                    $actividad  = $repository->findOneBy( array('idAlumno' => $id_alumno, 'idAsignatura' => $id_asignatura2, 'nombre' => $nombre_actividad));
                     //var_dump($actividad);
                    $valor = $notas[$clave];
                   
                    if($identificadores[0] === "nota"){
                        $valor_final = str_replace(",", ".", $valor);
                        $actividad->setNota($valor_final);
                    }else{
                        $actividad->setObservaciones($valor);
                    }
                    //var_dump($actividad);
                    //die();
                    $em->persist($actividad);
                    
                }
                
                $em->flush();
                
                return $this->redirect($this->generateUrl("si_sigue_calificar_profesor",array("id_asignatura"=>$id_asignatura)));
            
        }  
         public function exportar_calificacionesAction($id_asignatura){
                self::hayLogin();
                 $request = Request::createFromGlobals();
                 $tipo = $request->request->get("selector_tipo");
                 if($tipo === "plantilla"){
                     return self::exportar_plantilla($id_asignatura);
                 }
                 if($tipo === "campus"){
                     return self::exportar_campus($id_asignatura);
                 }
                 if($tipo === "gea"){
                     return self::exportar_gea($id_asignatura);
                 }
                 
                 
         }
        public function exportar_campus($id_asignatura){
                $kernel = $this->get('kernel');
                $dir_abs = self::getDireccionAbsoluta();
                $em = $this->getDoctrine()->getManager();
                $datos = self::listado_alumnos_actividad_asignatura($id_asignatura,"");
                $asignatura = $datos["asignatura"];             
                $resultados = $datos["resultados"];
                $lista_actividades = $datos["actividades"];
                
                $nombre_fichero ="calificaciones_".$asignatura->getNombre().".csv";
                /*Cabeceras*/
                $cabecera =  array("idMod","userName","Nombre","Apellidos");
               
                foreach($lista_actividades as $actividad){
                    array_push($cabecera,$actividad["nombre"]);
                }
                foreach($lista_actividades as $actividad){
                    array_push($cabecera,$actividad["nombre"]."-obs");
                }
                 array_push($cabecera,"numTokens");
                 array_push($cabecera,"notaTokens");
                 array_push($cabecera,"Nota Total");
                 $content["cabecera"] = $cabecera;
                 $contador = 0;
                
                foreach($resultados as $filas){                    
                    $fila = array();
                    array_push($fila,$filas["alumno"]->getIdAlumno());
                    array_push($fila,$filas["alumno"]->getIdAlumno());
                    array_push($fila,$filas["alumno"]->getNombre());
                    array_push($fila,$filas["alumno"]->getApellidos());                   
                    $array_actividades = $filas["actividades"];
                    $ac_nota = 0;
                    foreach($array_actividades as $fila_actividad ){
                      
                       //LA PROPIA NOTA DE LA ACTIVIDAD
                        $valor= $fila_actividad->getNota();
                        array_push($fila,$valor);  
                    
                        $ac_nota = $ac_nota +( $valor *  $fila_actividad->getPeso());
                   
                    }
                    foreach($array_actividades as $fila_actividad ){
                      
                       //LA PROPIA NOTA DE LA ACTIVIDAD
                        $valor = $fila_actividad->getObservaciones();
                        array_push($fila,$valor);  
                                                           
                    }

                        $codigos = $filas["codigos"];
                        $codigos = $codigos[0];
                        array_push($fila,$codigos->getNum()); 
                        /*Prediccion Nota TOkens*/
                        $nota_codigos = self::getNotaTokens($filas["alumno"]->getIdAlumno(),$asignatura);
                        array_push($fila,$nota_codigos); 
                        $ac_nota += $nota_codigos;      
                        array_push($fila,$ac_nota); 
                        $ac_nota = 0;                        
                        $content["fila".$contador] = $fila;
                        $contador++;
                }

                $mid = "/archivos/";
                if($kernel->getEnvironment() === "dev" ){
                    $mid = "/web/archivos/";
                }                
                $path = $_SERVER['DOCUMENT_ROOT'] .$mid.$nombre_fichero;
                $fp = fopen($path,"wb");
                foreach ($content as $fields) {
                     fputcsv($fp, $fields, chr(9));
                 }
                fclose($fp);
                
               $contenido = file_get_contents($path);
               $response = new Response();
               $response->headers->set('Content-Type', 'file/csv');
               $response->headers->set('Content-Disposition', 'attachment; filename='.$nombre_fichero);
               $response->setContent($contenido);
                             
               return $response;                             
        }
        
        public function exportar_gea($id_asignatura){
                $kernel = $this->get('kernel');
                $dir_abs = self::getDireccionAbsoluta();
                 $em = $this->getDoctrine()->getManager();
                require_once $dir_abs . '/vendor/Excel/lib/src/Classes/PHPExcel.php';
                $repo = $em->getRepository('SISigueBundle:Asignaturas');
                $asignatura = $repo->find($id_asignatura);  
                // Create new PHPExcel object
               
               $objPHPExcel = new \PHPExcel();

               // Set document properties
             
               $objPHPExcel->getProperties()->setCreator("ProyectoSigue")
                                                                        ->setLastModifiedBy("ProyectoSigue")
                                                                        ->setTitle("Office 2007 XLSX Test Document")
                                                                        ->setSubject("Office 2007 XLSX Test Document")
                                                                        ->setDescription("Calificador automatico")
                                                                        ->setKeywords("office 2007 openxml php");
                                  
               
               //AQUI DEBO IMPRIMIR TODOS LOS DATOS QUE HAY QUE MOSTRAR EN LA TABLA//
              $fila = "A";
              $columna = 1;
              $datos = self::listado_alumnos_actividad_asignatura($id_asignatura,"");
             
              $asignatura = $datos["asignatura"];
             
              $resultados = $datos["resultados"];
              $lista_actividades = $datos["actividades"];
               
              //Primero las cabeceras//
              $objPHPExcel->getActiveSheet()->setCellValue('A'.$columna, "Doc. de identidad");
              $objPHPExcel->getActiveSheet()->setCellValue('B'.$columna, "Alumno/a");
              $objPHPExcel->getActiveSheet()->setCellValue('C'.$columna, "Nota num.");
              $objPHPExcel->getActiveSheet()->setCellValue('D'.$columna, "Calificación");
             
              
              $fila="A";
              
             
              //AHORA LOS DATOS DE VERDAD.
              $ac_nota = 0; 
              $x  = 0;
              $columna = 2;
              
             foreach($resultados as $filas){
                   $fila= "A";
                   $y = 0; 
                   $dni =  $filas["alumno"]->getDni();
                   if(!$dni){$dni = "1234567";}
                   $objPHPExcel->getActiveSheet()->setCellValue($fila++.$columna,$dni);
                   $objPHPExcel->getActiveSheet()->setCellValue($fila++.$columna, $filas["alumno"]->getApellidos().", ".$filas["alumno"]->getNombre()); 
                    
                   
                   $array_actividades = $filas["actividades"];
                        foreach($array_actividades as $fila_actividad ){

                      
                        //Variables de control del input
                        $valor= $fila_actividad->getNota();
                       
                       
                        //LA PROPIA NOTA DE LA ACTIVIDAD
                        
                    
                     $ac_nota = $ac_nota +( $valor *  $fila_actividad->getPeso());
                    $y++;
                    }
                        $codigos = $filas["codigos"];
                         $codigos = $codigos[0];
                         $nota_codigos = self::getNotaTokens($filas["alumno"]->getIdAlumno(),$asignatura);
                         $ac_nota += $nota_codigos;
                         if($ac_nota > 10){
                             $ac_nota = 10;
                         }
                         $objPHPExcel->getActiveSheet()->setCellValue($fila++.$columna, $ac_nota);
                         $nota_letra = "";
                         if($ac_nota<5){
                             $nota_letra = "Suspenso";
                         }
                         else if($ac_nota < 7){
                             $nota_letra = "Aprobado";
                         }
                         else if($ac_nota < 9){
                             $nota_letra = "Notable";
                         }else{
                             $nota_letra = "Sobresaliente";
                         }
                          $objPHPExcel->getActiveSheet()->setCellValue($fila++.$columna, $nota_letra);                        
                      $ac_nota = 0;
                    
                   
               $columna++;
            }


               // Rename worksheet
             
               $objPHPExcel->getActiveSheet()->setTitle($asignatura->getNombre());
               $objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&G&C&HPlease treat this document as confidential!');
               $objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');
               
               // Set active sheet index to the first sheet, so Excel opens this as the first sheet
               
               $nombre_fichero =  "calificaciones ".$asignatura->getNombre().".xlsx";
               $nombre_ruta = $dir_abs."/web/archivos/lista_calificaciones/".$nombre_fichero;
               $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
               $path = self::getDireccionAbsoluta() ."/web/archivos/lista_calificaciones/". $nombre_fichero;
               $objWriter->save($path);
               
               $content = file_get_contents($path);
               $response = new Response();
               $response->headers->set('Content-Type', 'file/xlsx');
               $response->headers->set('Content-Disposition', 'attachment; filename='.$nombre_fichero);
               $response->setContent($content);
                             
               return $response;                             
        }
        
        
        public function exportar_plantilla($id_asignatura){
                $kernel = $this->get('kernel');
                $dir_abs = self::getDireccionAbsoluta();
                 $em = $this->getDoctrine()->getManager();
                require_once $dir_abs . '/vendor/Excel/lib/src/Classes/PHPExcel.php';
                $repo = $em->getRepository('SISigueBundle:Asignaturas');
                $asignatura = $repo->find($id_asignatura);  
                // Create new PHPExcel object
               
               $objPHPExcel = new \PHPExcel();

               // Set document properties
             
               $objPHPExcel->getProperties()->setCreator("ProyectoSigue")
                                                                        ->setLastModifiedBy("ProyectoSigue")
                                                                        ->setTitle("Office 2007 XLSX Test Document")
                                                                        ->setSubject("Office 2007 XLSX Test Document")
                                                                        ->setDescription("Calificador automatico")
                                                                        ->setKeywords("office 2007 openxml php");
                                  
               
               //AQUI DEBO IMPRIMIR TODOS LOS DATOS QUE HAY QUE MOSTRAR EN LA TABLA//
              $fila = "A";
              $columna = 1;
              $datos = self::listado_alumnos_actividad_asignatura($id_asignatura,"");
             
              $asignatura = $datos["asignatura"];
             
              $resultados = $datos["resultados"];
              $lista_actividades = $datos["actividades"];
               
              //Primero las cabeceras//
              $objPHPExcel->getActiveSheet()->setCellValue('A'.$columna, $asignatura->getNombre());
              $objPHPExcel->getActiveSheet()->setCellValue('B'.$columna, $asignatura->getId());
              $objPHPExcel->getActiveSheet()->setCellValue('A2', "Id Alumno");
              $objPHPExcel->getActiveSheet()->setCellValue('B2', "Nombre");
              $objPHPExcel->getActiveSheet()->setCellValue('C2', "Apellidos");
              
              $fila="D";
              foreach($lista_actividades as $actividad){
                   $objPHPExcel->getActiveSheet()->setCellValue($fila.$columna, $actividad["nombre"]);
                   $objPHPExcel->getActiveSheet()->setCellValue($fila.($columna+1), $actividad["peso"]);
                   $fila++;
              }
              $objPHPExcel->getActiveSheet()->setCellValue($fila.($columna+1), "TOKENS");
              $fila++;
              $objPHPExcel->getActiveSheet()->setCellValue($fila.($columna+1), "NOTA TOTAL(aprox)");
            
                //AHORA LOS DATOS DE VERDAD.
             $ac_nota = 0; 
              $x  = 0;
              $columna = 3;
              
             foreach($resultados as $filas){
                    $fila= "A";
                   $y = 0; 
                   $objPHPExcel->getActiveSheet()->setCellValue($fila++.$columna, $filas["alumno"]->getIdAlumno());                    
                    $objPHPExcel->getActiveSheet()->setCellValue($fila++.$columna, $filas["alumno"]->getNombre()); 
                    $objPHPExcel->getActiveSheet()->setCellValue($fila++.$columna, $filas["alumno"]->getApellidos()); 
                   $array_actividades = $filas["actividades"];
                        foreach($array_actividades as $fila_actividad ){

                      
                        //Variables de control del input
                        $valor= $fila_actividad->getNota();
                       
                       
                        //LA PROPIA NOTA DE LA ACTIVIDAD
                        $objPHPExcel->getActiveSheet()->setCellValue($fila++.$columna, $valor); 
                    
                     $ac_nota = $ac_nota +( $valor *  $fila_actividad->getPeso());
                    $y++;
                    }
                        $codigos = $filas["codigos"];
                        $codigos = $codigos[0];
                   
                      $objPHPExcel->getActiveSheet()->setCellValue($fila++.$columna, $codigos->getNum()) ;
                      $nota_codigos = self::getNotaTokens($filas["alumno"]->getIdAlumno(),$asignatura);                      
                      $ac_nota += $nota_codigos;      
                      $objPHPExcel->getActiveSheet()->setCellValue($fila++.$columna, $ac_nota);
                      $ac_nota = 0;
                    
                   
               $columna++;
            }


               // Rename worksheet
             
               $objPHPExcel->getActiveSheet()->setTitle($asignatura->getNombre());
               $objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&G&C&HPlease treat this document as confidential!');
               $objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');
               
               // Set active sheet index to the first sheet, so Excel opens this as the first sheet
               
               $nombre_fichero =  "calificaciones ".$asignatura->getNombre().".xlsx";
               $nombre_ruta = $dir_abs."/web/archivos/lista_calificaciones/".$nombre_fichero;
               $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
               $path = self::getDireccionAbsoluta() ."/web/archivos/lista_calificaciones/". $nombre_fichero;
               $objWriter->save($path);
               
               $content = file_get_contents($path);
               $response = new Response();
               $response->headers->set('Content-Type', 'file/xlsx');
               $response->headers->set('Content-Disposition', 'attachment; filename='.$nombre_fichero);
               $response->setContent($content);
                             
               return $response;                             
        }
        
         public function importar_calificacionesAction(){
             self::hayLogin();
            $kernel = $this->get('kernel');
            $dir_abs = self::getDireccionAbsoluta();
            require_once $dir_abs . '/vendor/Excel/lib/src/Classes/PHPExcel.php';
            
             $request = Request::createFromGlobals();
            /*PARA SUBIR UNA LISTA EXCEL DE ALUMNOS, HAY QUE:
             * 1º PROCESAR EL FICHERO \/
             * 2º GUARDAR CADA FILA EN LA TABLA ALUMNOS (con la entity Alumno) \/
             * 3º GUARDAR EL COMBO PROFESOR-ASIGNATURA \/
             * 
             */
            
             $exito = "none";  
             if( $kernel->getEnvironment() === "dev" ){
                 /*Si quereis que funcione en vuestro desarrollo localhost cambiad esta ruta.*/                                               
                 //DIEGO:
                $uploaddir = self::getDireccionAbsoluta()."/web/archivos/";
                $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
               
             }else{
                $uploaddir = self::getDireccionAbsoluta().'/web/archivos/';
                $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
               
                }               
                if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                   $exito ="true";
                } else {
                   $exito ="false";
                }
             /*Se ha cargado y vamos a procesarlo para pintarlo*/
                    $inputFileName = $uploadfile;
                                             
                    /**  Identify the type of $inputFileName  **/
                    $inputFileType = \PHPExcel_IOFactory::identify($inputFileName);
                    /**  Create a new Reader of the type that has been identified  **/
                    $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
                    
                    $objPHPExcel = $objReader->load($inputFileName);

                    $objWorksheet = $objPHPExcel->getActiveSheet();
                    
                    //////////////////////////////////////////////////////////////////////////
                    /*A partir de aqui debemos leer todos los datos.*/
                    $id_asignatura = $request->request->get('id_asignatura', 'default');

                    

                    $session = $this->get("session");
                    $idprofesor =$session->get('idprofesor');
                  
                    
                    foreach ($objWorksheet->getRowIterator() as $row) {                        

                        $cellIterator = $row->getCellIterator();
                        $cellIterator->setIterateOnlyExistingCells(true); // This loops all cells iterated.                        
                        self::subir_calificacion($cellIterator, $id_asignatura, $objPHPExcel);                    
                    }
                   
                   return $this->calificarAction($id_asignatura);
        }
        
         public function subir_calificacion($fila, $id_asignatura, $objPHPExcel ){
            $em = $this->getDoctrine()->getManager();            
            $alumno = new Alumnos();
            $actividad = new ActividadAsignatura();
            // var_dump($fila);die();
            foreach($fila as $celda){   
               
                //var_dump($celda->getColumn().$celda->getRow());
                if($celda->getRow() > 2){
                    //echo "COLMUNAS " . var_dump($celda->getColumn());
                    switch ($celda->getColumn()){
                        case "A":
                                //Esta debe ser la id del alumno
                                if(!$alumno->getIdalumno()){
                                    $alumno = $em->getRepository("SISigueBundle:Alumnos")->findOneByIdalumno($celda->getValue());
                                   // var_dump($alumno);
                                }
                                break;
                        case "B":
                                //Nombre
                                
                                break;
                        case "C":
                                //Apellidos
                                
                                break;                        
                        default: 
                            $celda_cabecera = $celda->getColumn()."1";
                            $celda_peso = $celda->getColumn()."2";
                            $nombre_actividad = $objPHPExcel->getActiveSheet()->getCell($celda_cabecera)->getValue();
                            $peso_actividad = $objPHPExcel->getActiveSheet()->getCell($celda_peso)->getValue();
                           // var_dump($nombre_actividad);
                            if(is_null($nombre_actividad) || $nombre_actividad === ""){
                                //fin de las actividades
                            }else{
                               //tenemos nombre, y alumno al que tratar. 
                                //1º Averiguar si la actividad ya existe
                                //2º Si no existe se crea
                                //3º Se asigna nota a ese alumno
                                $actividad = $em->getRepository("SISigueBundle:ActividadAsignatura")->findOneBy(array("nombre" => $nombre_actividad, "idAlumno" => $alumno->getidalumno()));
                                //var_dump($actividad);
                                if(!is_null($actividad) && $actividad->getNombre()){
                                    $valor_final = str_replace(",", ".", $celda->getValue());
                                    $actividad->setNota($valor_final);
                                    $actividad->setPeso($peso_actividad);
                                     $em->persist($actividad);
                                }else{
                                    //Si es una actividad nueva: Tengo que crearla.
                                    self::nueva_actividad($id_asignatura,$nombre_actividad,$peso_actividad,"");
                                    $actividad = $em->getRepository("SISigueBundle:ActividadAsignatura")->findOneBy(array("nombre" => $nombre_actividad, "idAlumno" => $alumno->getidalumno()));
                                    $valor_final = str_replace(",", ".", $celda->getValue());
                                    $actividad->setNota($valor_final);
                                    $actividad->setNota($celda->getValue());
                                    //$actividad->setPeso($peso_actividad);
                                     $em->persist($actividad);
                                }

                            }
                            break;
                    }
                }else{
                   
                    return;
                }
               // die();
                if($actividad->getNombre()){
                    
                    $em->persist($actividad);
                   
                }
                
           }
           
           $em->flush();
           return true;
        }
        
        public function add_profesor_asignaturaAction($exito = ""){
            self::hayLogin();
            if($exito ==="no") {
                $exito = "";
            }
            //Lista de asignaturas impartidas por el profesor//
            // var_dump($exito);die(); 
            $asig = self::getAsignaturas();
            $profesor = self::getProfesor();
            $asig_prof =  array();
            $em = $this->getDoctrine()->getManager();
            //Por cada asignatura, consulto los profesores que NO la imparten
            foreach($asig as $asignatura){
                // var_dump($asignatura[0]); die();
               $prof_si = $em->createQuery('
                            SELECT IDENTITY (p.idProfesor)
                            FROM SISigueBundle:ProfesorAsignatura p     
                            WHERE p.idAsignatura = :idAsignatura   

                        ')->setParameter('idAsignatura',$asignatura[0]->getId());  
                 $profesores_si = $prof_si->getResult();
             //  var_dump($profesores_si);die(); 
                 $prof_no  = $em->createQuery('
                            SELECT prof
                            FROM SISigueBundle:Profesor prof     
                            WHERE prof.idprofesor NOT IN ('.$prof_si->getDQL().')
                                
                            ')->setParameter('idAsignatura',$asignatura[0]->getId());  ;
                 //var_dump($prof_no->getDQL());die();
                 $profesores_no = $prof_no->getResult();
                 //var_dump($profesores_no);die(); 
              $array_pa = array("asignatura" => $asignatura, "profesores" => $profesores_no);
              array_push($asig_prof, $array_pa);
                 
                 
            }
               
           $resultado["exito"] = $exito;
           $resultado["asignaturas"] = $asig;
           $resultado["asig_prof"] = $asig_prof;
           $resultado["asignatura"] = null;
           
               
               
            return $this->render('SISigueBundle:Profesor:add_profesor.html.php',$resultado);
        }
        
        public function add_asignaturaAction($exito = ""){
            
            self::hayLogin();
             $em = $this->getDoctrine()->getManager();             
             $asignaturas = self::getAsignaturas();
             //var_dump($asignaturas);die();
             $array =  array();
             $array["asignaturas"] = $asignaturas; 
             $array["asignatura"] = null; 
             $array["exito"] = "";
             if($exito != ""){
                $array["exito"] = "true2";                          
             }
             
            return $this->render('SISigueBundle:Profesor:add_asignatura.html.php', $array);
            
            
        }
        
       
        
        
          public function add_profesor_asignatura_guardarAction(){
              self::hayLogin();
               $request = Request::createFromGlobals();
               $em = $this->getDoctrine()->getManager();
              // $asig = $request->request->get('cantidad');
              // $id_asignatura = $request->request->get('id_asignatura');
               $array_total = $request->request->all();
               // var_dump($array_total); die();
                $array_claves = array_keys($array_total);
                $i = 0;
               foreach($array_total as $asignaturas){                  
                   $id_as_str = $array_claves[$i];
                  
                   $id_asig = explode("_", $id_as_str);
                   $id_asignatura = $id_asig[1];
                   foreach($asignaturas as $idprof){
                       $asig_prof = new ProfesorAsignatura();
                       
                       $asig_prof->setIdAsignatura($id_asignatura);
                       $profesor = $em->getRepository('SISigueBundle:Profesor')->findOneByIdprofesor($idprof);
                       $asig_prof->setIdprofesor($profesor);
                       //var_dump($asig_prof); die();
                       $em->persist($asig_prof);
                   }
                   $i++;
               }
                $em->flush();
               //var_dump(); die();
              
             return $this->redirect($this->generateUrl("si_sigue_add_profesor_asignatura",array("exito" => "true")));
          }    
        
        public function generar_actividadAction(){
            self::hayLogin();
            $request = Request::createFromGlobals();
            $em = $this->getDoctrine()->getManager(); 
            $id_asignatura = $request->request->get("id_asignatura");
            $nombre = $request->request->get("nombre");
            $pesoStr = $request->request->get("peso");
            $descripcion = $request->request->get("descripcion");
            $nueva = $request->request->get("nueva");
            $nombre_antiguo = $request->request->get("nombre_antiguo");
            $eliminar = $request->request->get("eliminar");
            if($eliminar ==="eliminar"){
                $actividades = $em->getRepository('SISigueBundle:ActividadAsignatura')->findBy(array("nombre" =>$nombre_antiguo, "idAsignatura"=>$id_asignatura));
                foreach($actividades as $actividad){                        
                        $em->remove($actividad);
                    }
            }else{
            
                if($nueva ==="si"){
                    $peso = intval($pesoStr)/100;   
                    self::nueva_actividad($id_asignatura,$nombre,$peso,$descripcion);            
                }else{
                    $actividades = $em->getRepository('SISigueBundle:ActividadAsignatura')->findBy(array("nombre" =>$nombre_antiguo, "idAsignatura"=>$id_asignatura));
                    foreach($actividades as $actividad){
                        $actividad->setNombre($nombre);
                        $actividad->setPeso(intval($pesoStr)/100);
                        $actividad->setDescripcion($descripcion);
                        $em->persist($actividad);
                    }
                   
                }
            
            }
             $em->flush();
            return $this->calificarAction($id_asignatura);
             //return $this->render('SISigueBundle:Profesor:actividad.html.php');
        }
            
        
        private function nueva_actividad($id_asignatura,$nombre,$peso,$descripcion){
             
            $date_time_zone = new \DateTimeZone("Europe/Madrid");
            $date_time = new \DateTime("now",$date_time_zone);
            $em = $this->getDoctrine()->getManager();            
            $asignatura = $em->getRepository('SISigueBundle:Asignaturas')->find($id_asignatura);
            //var_dump($asignatura);
            $alumnos = $em->getRepository('SISigueBundle:AsignaturaAlumno')->findByIdAsignatura($id_asignatura);
            //var_dump($alumnos);
            foreach($alumnos as $al){
                $id_al = $al->getIdAlumno();
                //var_dump($id_al);
                $alumno = $em->getRepository('SISigueBundle:Alumnos')->findByIdalumno($id_al);
                //ar_dump($alumno);
                $actividad = new ActividadAsignatura();
                $actividad->setIdAlumno($alumno[0]);
                $actividad->setIdAsignatura($asignatura);
                $actividad->setNombre($nombre);
                $actividad->setPeso($peso);
                $actividad->setDescripcion($descripcion);
                $actividad->setFechaCreacion($date_time);
                $em->persist( $actividad);
                //var_dump($actividad);
           
             }
             //die();
             $em->flush();
            
        }
        
        private function hashSSHA($password) {
        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
        
        }
            
            public function crearImgCodigos($lista_codigos){
                require_once '../vendor/PHPqrcode/phpqrcode.php';
                $kernel = $this->get('kernel');
                $i = 0;
                $lista_archivos = array();
                $dir_abs = self::getDireccionAbsoluta();
                foreach ($lista_codigos as $codigo){
                    //Generar el png con el código qr
                   
                    $ruta = $dir_abs.'/web/archivos/codigosQR/qr_'.$i.'.png';
                   /*
                        var_dump($dir_abs.$ruta);                     
                        die();
                   */ 
                    if($kernel->getEnvironment() === "dev"){
                        $ruta = \str_replace("/","\\",$ruta);
                    }
                    \QRcode::png($codigo->getCodigo(),$ruta ,QR_ECLEVEL_H,6,4,true);
                    //array_push($lista_archivos,$ruta);
                    $lista_archivos[$i] = array(0 => $ruta,1 => $codigo->getCodigo(),2 => $codigo->getId());
                    $i++;
                }
                //var_dump($lista_archivos);
                //die();
                return $lista_archivos;
            }
            
            public function crearPdfCodigos($imgCodigos){
                require_once '../vendor/FPDF/fpdf.php';
                $kernel = $this->get("kernel");
                $dir_abs = self::getDireccionAbsoluta();
                $pdf = new \FPDF();
                $i = 0;
                $k = 21;
                $x = 5;
                $y = 40;
                $esp = 150;
                //dibujar 4 pdf por hoja
                foreach($imgCodigos as $codigo){
                    $p = ($i % 4);
                    if ($p == 0){
                        $pdf->AddPage();
                        $pdf->SetFont('Arial','B',11);
                        //lineas divisorias
                        self::colocarQR($pdf,$x,$y,$codigo,10,$dir_abs);
                        self::margenes($pdf,$x,10);
                    }else if ($p == 1){
                        self::colocarQR($pdf,$x*$k,$y,$codigo,10,$dir_abs);
                        self::margenes($pdf,$x*$k,10);
                        $pdf->Ln($esp);
                    }else if ($p == 2){
                        self::colocarQR($pdf,$x,$y+$esp,$codigo,160,$dir_abs);
                        self::margenes($pdf,$x,160);
                    }else if ($p == 3){
                        self::colocarQR($pdf,$x*$k,$y+$esp,$codigo,160,$dir_abs);
                        self::margenes($pdf,$x*$k,160);
                    }
                    $i = $i + 1;                
                }
                 $ruta = self::getDireccionAbsoluta()."/web/archivos/pdfs/";
                  if($kernel->getEnvironment() === "dev"){
                        $ruta = \str_replace("/","\\",$ruta);
                    }
                 $date_time_zone = new \DateTimeZone("Europe/Madrid");
                 $date_time = new \DateTime("now",$date_time_zone);                
                 $fichero = "lista_codigos".$date_time->getTimestamp().".pdf";
                 $ruta2 = $ruta . $fichero;
                
                 $pdf->Output($ruta2,"F" );
                 return $fichero;
            }
            
            public function getDireccionAbsoluta(){
                
                $kernel = $this->get("kernel");
                $dir_abs = $kernel->getRootDir();
                //Aqui tenemos la direccion hasta app, hay que volver y paso.
                $dir_abs = explode('/', $dir_abs);
                array_pop($dir_abs);
                $dir_abs = implode('/', $dir_abs);
                return $dir_abs;
            }
            
            private function getAsignaturas(){
                // $peticion = $this->getRequest()->getSession();
                 $peticion = $this->container->get('session');
                $id = $peticion->get('idprofesor');
                $em = $this->getDoctrine()->getManager();
                $asignaturas = $em->getRepository('SISigueBundle:ProfesorAsignatura')->findBy(array('idProfesor' => $id));
                //var_dump($asignaturas);
                $asig = array();
                foreach ($asignaturas as $a){
                    $as = $em->getRepository('SISigueBundle:Asignaturas')->findBy(array('id' => $a->getIdAsignatura()));
                    array_push($asig, $as);         
                    //aqui le metemos en un array prof_left, la lista de profesores que NO imparten la asignatua
                    //para poder añadirlos despues.
            }
                return $asig;
            }
            
            private function getProfesor(){
                $peticion = $this->container->get('session');
                $id = $peticion->get('idprofesor');
                if(!isset($id) || is_null($id)|| $id===""){
                    return "session_lost";
                }
                $em = $this->getDoctrine()->getManager();
                $profesor = $em->getRepository('SISigueBundle:Profesor')->findOneByIdprofesor($id);
                return $profesor;
            }
            
            private function colocarQR($pdf,$x,$y,$codigo,$j,$dir_abs){
                //cabecera
                $pdf->Image($dir_abs.'/web/img/cabecera.jpg',$x,$j,80,10);
                $pdf->setXY($x + 5,$j+10);
                //contenido
                $pdf->Cell(100,10,$codigo[1]);
                $asig = 'Grupo '.$codigo[2]->getGrupo().', curso '.$codigo[2]->getCurso();
                $pdf->Text($x + 5, $y-8, $codigo[2]->getNombre());
                $pdf->Text($x + 5, $y-3, $asig);
                $pdf->Image($codigo[0],$x+15,$y,65,65);
                //pie de pagina
                $pdf->Image($dir_abs.'/web/img/linea.jpg',$x,$y+75,100,2);
            }
            
            private function listado_alumnos_actividad_asignatura($id_asignatura, $exito){
                 $em = $this->getDoctrine()->getManager();
                $repo = $em->getRepository('SISigueBundle:Asignaturas');
                $asignatura = $repo->find($id_asignatura);  
                //Tengo que sacar la lista de alumnos de la asignatura
                //Luego, por cada alumno, saco la lista de actividades que tiene asociado
                // y se lo añado a un array listo para mostrar.

                $asig_alumnos = $em->getRepository('SISigueBundle:AsignaturaAlumno')->findByIdAsignatura($asignatura);
                //Para poder mostrarlo correctamente necesito la info de las actividades, sin importar 
                $actividades = $em->createQuery('
                            SELECT distinct(p.nombre) as nombre, p.descripcion, p.peso
                            FROM SISigueBundle:ActividadAsignatura p     
                            WHERE p.idAsignatura = :idAsignatura   

                        ')->setParameter('idAsignatura', $id_asignatura);
                $actividades_info = $actividades->getResult();
               // var_dump($actividades_info);
               // die();
                $resultado = array();
                $parcial = array();
                foreach($asig_alumnos as $alumnos){
                    $alumno = $alumnos->getIdAlumno();
                    //$repo_actividades = $em->getRepository('SISigueBundle:ActividadAsignatura');
                    $query = $em->CreateQuery(
                            'SELECT p
                             FROM SISigueBundle:ActividadAsignatura p
                                WHERE p.idAsignatura = :idAsignatura  
                                AND p.idAlumno = :idAlumno
                                '
                            )->SetParameters(array('idAsignatura'=>$id_asignatura, 'idAlumno'=>$alumno));
                    $actividades = $query->getResult();
                    $query2 = $em->CreateQuery(
                                'SELECT cod
                                FROM SISigueBundle:AsignaturaAlumno cod
                                where cod.idAsignatura = :idAsignatura
                                AND cod.idAlumno = :idAlumno
                                ')->SetParameters(array('idAsignatura'=>$id_asignatura, 'idAlumno'=>$alumno));
                    $codigos = $query2->getResult();
                    
                    
                    
                    $parcial = array("alumno" => $alumno, "actividades" => $actividades, "codigos"=>$codigos);
                    $parcial["nota_tokens"] = self::getNotaTokens($alumno->getIdalumno(),$asignatura);
                    array_push($resultado, $parcial);
                }
                return array("asignatura"=>$asignatura,"resultados"=>$resultado, "actividades"=>$actividades_info, "exito" => $exito);
            }
            
            private function margenes($pdf,$x,$j){
                $pdf->Line($x, $j, $x+100, $j);
                $pdf->Line($x, $j+115, $x+100, $j+115);
                $pdf->Line($x, $j, $x, $j+115);
                $pdf->Line($x+100, $j, $x+100, $j+115);

                $pdf->Line($x,$j-10,$x,$j-5);
	        $pdf->Line($x+100,$j-10,$x+100,$j-5);
	        $pdf->Line($x,$j+120,$x,$j+125);
                $pdf->Line($x+100,$j+120,$x+100,$j+125);
            }
            
            
            public function getCodigoEncriptadop(){
                $profesor = self::getProfesor();               
                return self::getCodigoEncriptado($profesor);
            }
            
            private function getCodigoEncriptado($profesor){
                $key = "sigue";
                $string = $profesor->getCodigo();
               
                $result = ""; 
                for($i=0; $i<strlen($string); $i++) {
                    $char = substr($string, $i, 1);
                    $keychar = substr($key, ($i % strlen($key))-1, 1);
                    $char = chr(ord($char)+ord($keychar));
                    $result .= $char;
                }
                
                return base64_encode($result);
            }
            
            private function hayLogin(){
                 $profesor = self::getProfesor();
                 if($profesor === "session_lost"){
                    $session = $this->container->get('session');
                    $session->remove('idalumno');
                    $session->remove('idprofesor');
                    $this->redirect('/index.php');
                }
                
            }
        
        private function getNotaTokens($id_alumno,$asignatura){
           $em =  $this->getDoctrine()->getManager();            
           $predicciones = self::getPrediccion($em,$id_alumno,$asignatura,$asignatura->getIdeval(),$asignatura->getParameval());
            
           return $predicciones;
            
        }    
            
            
            
        private function getPrediccion($em,$id,$asig,$metodoEval,$param){
        
        if(isset($metodoEval)){    
        $idEval = $metodoEval->getIdeval();
        $p = array();
        $prediccion = 0;
        switch ($idEval){
            case 1: $p = self::getParametrosOpcion1($param);
                    $prediccion = self::prediccionNotaOpcion1($em,$id,$asig,$p[0]);
                break;
            case 2: $p = self::getParametrosOpcion2($param);
                    $prediccion = self::prediccionNotaOpcion2($em,$id,$asig,$p[0],$p[1],$p[2]);
                break;
            case 3: $p = self::getParametrosOpcion3($param);
                    $prediccion = self::prediccionNotaOpcion3($em,$id,$asig,$p[0],$p[1],$p[2]);
                break;
        }
        return number_format($prediccion,2);
        
        }else{
            return 0;
        }
    }
    
    private function getParametrosOpcion1($param){
       $vars = explode("##",$param);
        parse_str($vars[0],$out1);
        parse_str($vars[1],$out2);       
        $p = array();
        $p[0] = floatval($out1['varor_absoluto']);  
        $p[1] = floatval($out2['peso_nota_final']/100);     
        return $p;
    }
    
    private function getParametrosOpcion2($param){
        $vars = explode("##",$param);
        parse_str($vars[0],$out1);
        parse_str($vars[1],$out2);
        parse_str($vars[2],$out3);
        $p = array();
        $p[0] = floatval($out1['margen_tolerancia']);  
        $p[1] = floatval($out2['num_notas_descartar']);
        $p[2] = floatval($out3['peso_nota_final'])/100;
        return $p;
    }
    
    private function getParametrosOpcion3($param){
        $vars = explode("##",$param);
        parse_str($vars[0],$out1);
        parse_str($vars[1],$out2);
        parse_str($vars[2],$out3);
        $p = array();
        $p[0] = floatval($out1['nota_referencia']);  
        $p[1] = floatval($out2['minimo_tokens']);
        $p[2] = floatval($out3['peso_nota_final'])/100;
        return $p;
    }
    
    private function prediccionNotaOpcion1($em,$id,$asig,$peso,$peso_total){
        $query = $em->getRepository('SISigueBundle:AsignaturaAlumno')->findOneBy(array('idAsignatura'=> $asig,'idAlumno'=>$id));
        $num = $query->getNum();
        $valor = $num*$peso;
        if ($valor > 10) return 10 *$peso_total;
        return $valor *$peso_total;
    }
    
    private function prediccionNotaOpcion2($em,$id,$asig,$tolerancia,$descartes,$peso){
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
        if ($num >= $limite) return 10 * $peso;
        return ($num*10/$limite) *$peso;
        
    }
    
    private function prediccionNotaOpcion3($em,$id,$asig,$n,$x,$peso){
        $queryNum = $em->createQuery(  'SELECT COUNT (T)
                                        FROM SISigueBundle:Codigos T
                                        WHERE T.id = :asig
                                        GROUP BY T.id'
                                        )->setParameter('asig',$asig);
        $numTokens = 0;
        if(count($queryNum->getResult())>0){
            $result = $queryNum->getResult();
            $numTokens = intval($result[0][1]);
        }else{
            return 0;
        }
        $queryN = $em->createQuery(  'SELECT COUNT (T)
                                    FROM SISigueBundle:AsignaturaAlumno T
                                    WHERE T.idAsignatura = :asig AND T.num >= :n'
                                    )->setParameter('asig',$asig)
                                     ->setParameter('n',$n);
        $result2 = $queryN->getResult();
        $numAl = intval($result2[0][1]);
        
        $numX = $numTokens/$numAl;
        $query = $em->getRepository('SISigueBundle:AsignaturaAlumno')->findOneBy(array('idAsignatura'=> $asig,'idAlumno'=>$id));
        $num = $query->getNum();
        if ($num == $numX) return $x *$peso;
        return ($num*$x/$numX)*$peso ;
    }
    
    
    
    
    function sendMessageToPhone($collapseKey, $messageText, $username)
        {
         
         

          $apiKey = 'AIzaSyDPw2X129Zb6mWaqZgXylwrX6LzcVkuAKw';
          /*Aqui tengo que enviar las notificaciones para todos los alumnos*/
           $userIdentificador = array();
          foreach($username as $usuario){              
            array_push($userIdentificador, $usuario->getGcmcode());
          }
          //var_dump($userIdentificador);die();
          $headers = array();
          array_push($headers,"Content-Type:application/json");
          array_push($headers,'Authorization:key=' . $apiKey);
          
          $data = array(
            'registration_ids' => $userIdentificador,
            'collapse_key' => $collapseKey,
            'data.message' => $messageText,
            'message' => $messageText);

          $ch = curl_init();
         
          curl_setopt($ch, CURLOPT_URL, "https://android.googleapis.com/gcm/send");
          if ($headers)
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));
                       
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (curl_errno($ch)) {
             return 'fail';
            }
            
            if ($httpCode != 200) {
             return 'status code '.$httpCode;
            }
            curl_close($ch);
            return $response;
        
        } 

            
            
    }
  
?>
