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

class ProfesorController extends Controller
    {
        public function indexAction($exito)
        {     
           $asig = self::getAsignaturas();
            
           if(!is_array($exito)){                           
               return $this->render('SISigueBundle:Profesor:index.html.php',array("exito" => $exito,'asignaturas' =>$asig));
           }else{
               $asig2 = array("asignaturas" => $asig);
               if(! array_key_exists("alumnos",$exito)){
                  $exito = array_merge($exito, array("alumnos" => null)) ;
               }
               $exito = array_merge($exito,$asig2);
               //var_dump($exito);
              
               return $this->render('SISigueBundle:Profesor:index.html.php',$exito);
           }              
        }
        public function subir_alumnoAction(){
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
                 
                //HECTOR:
                //$uploaddir = 'C:\Users\j\Documents\proyecto-sigue\web\web\archivos';
                 //DIEGO:
                 $uploaddir = self::getDireccionAbsoluta()."/web/archivos/";
                $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
               
             }else{
                $uploaddir = '/home/administrador/web/Symfony/web/archivos/';
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
                $existe_alumno = $em->getRepository('SISigueBundle:Alumnos')->findByCorreo($alumno->getCorreo());
                if(!$existe_alumno){
                    $pass_provisional = explode("@",$alumno->getCorreo());
                    $pass_provisional[0] = $pass_provisional[0].rand(0,99);
                    
                    $hash = self::hashSSHA($pass_provisional[0]);
                    $encrypted_password = $hash["encrypted"];
                    $salt = $hash["salt"];
                    $alumno->setSalt($salt);
                    $alumno->setPassword($encrypted_password);
                    //$alumno->setPassword($pass_provisional[0]);
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
        
        
        
        public function generar_qrAction(){
                $request = Request::createFromGlobals();
                $cantidad = $request->request->get('cantidad');
                $id_asignatura = $request->request->get('id_asignatura');
                $lista_codigos = array();
                $codigo = new Codigos();
                $em = $this->getDoctrine()->getManager();
                $asignatura = $em->getRepository('SISigueBundle:Asignaturas')->find($id_asignatura);
                // var_dump($asignatura);
                for($i = 0;$i < $cantidad; $i++){
                    $cuerpo_codigo = $unique_key = substr(md5(rand(0, 1000000)), 0, 15 );
                    // var_dump($cuerpo_codigo);                  
                    $codigo = $em->getRepository('SISigueBundle:Codigos')->findByCodigo( $cuerpo_codigo);
                    if(!$codigo){
                        $codigo = new Codigos();
                        $codigo->setCodigo($cuerpo_codigo);
                        $codigo->setId($asignatura);
                        $date_time = new \DateTime();
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
                
                
                
                return $this->indexAction(array("exito" => "true2", "ruta_pdf" => $ruta_pdf));
                
            }
               
        public function statsAction($id_asignatura){
            /*Tengo que sacar los codigos asociados a esa asignatura 
             * que estén usados por algún alumno, y sacar los pares alunmo, nºcodigos*/
           $em = $this->getDoctrine()->getManager();
           $asignatura = $em->getRepository('SISigueBundle:Asignaturas')->find($id_asignatura);
           $alumnos = $em->getRepository('SISigueBundle:AsignaturaAlumno')->findByIdAsignatura($asignatura);
           $exito = "stats_".$asignatura->getId();
           //var_dump($alumnos);
          // die();
           
           $asignaturas = self::getAsignaturas();
           $array = ["exito" => $exito, "alumnos" =>$alumnos ];           
           return $this->indexAction($array);
        }
        
        public function actividadAction($id_asignatura){            
            $em = $this->getDoctrine()->getManager();
            $repo = $em->getRepository('SISigueBundle:Asignaturas');
            $asignatura = $repo->find($id_asignatura);
            //$actividades = $em->getRepository('SISigueBundle:ActividadAsignatura')->findBy(array('idAsignatura'=>$id_asignatura), array("fechaCreacion"=>"ASC"), array('groupBy'=>'nombre'));
            $query = $em->createQuery(
                    'SELECT p
                    FROM SISigueBundle:ActividadAsignatura p
                    WHERE p.idAsignatura = :idAsignatura
                    GROUP BY p.nombre
                    ORDER BY p.fechaCreacion ASC                   
                    '
                )->setParameter('idAsignatura', $id_asignatura);
            $actividades = $query->getResult();
            $act_resultados = array();
            $repo = $em->getRepository('SISigueBundle:ActividadAsignatura');
            foreach ($actividades as $actividad) {                              
               $resultado =  $repo->findByNombre($actividad->getNombre());                  
               array_push($act_resultados, $resultado);
            }
            //var_dump($act_resultados);die();
            return $this->render('SISigueBundle:Profesor:actividad.html.php', array("asignatura"=>$asignatura, "actividades"=>$actividades, "resultados"=>$act_resultados));
        }
        
        public function generar_actividadAction(){
            $request = Request::createFromGlobals();
             
            $id_asignatura = $request->request->get("id_asignatura");
            $nombre = $request->request->get("nombre");
            $pesoStr = $request->request->get("peso");
            $descripcion = $request->request->get("descripcion");
            $peso = intval($pesoStr)/100;            
            $date_time = new \DateTime();
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
            return $this->actividadAction($id_asignatura);
             //return $this->render('SISigueBundle:Profesor:actividad.html.php');
        }
            
            
        private function hashSSHA($password) {
        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
        
        }
            
            public function crearImgCodigos($lista_codigos){
                require '../vendor/PHPqrcode/phpqrcode.php';
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
                     * 
                     */
                    if($kernel->getEnvironment() === "dev"){
                        $ruta = \str_replace("/","\\",$ruta);
                    }
                    \QRcode::png($codigo->getCodigo(),$ruta ,QR_ECLEVEL_H,6,4,true);
                    array_push($lista_archivos,$ruta);
                    $i++;
                }
                //var_dump($lista_archivos);
                //die();
                return $lista_archivos;
            }
            
            public function crearPdfCodigos($imgCodigos){
                
                require '../vendor/FPDF/fpdf.php';
                $kernel = $this->get("kernel");
                $pdf = new \FPDF();
                $i = 0;
                $x = 10;
                $y = 20;
                $esp = 150;
                foreach($imgCodigos as $codigo){
                    $p = ($i % 4);
                    if ($p == 0){
                        $pdf->AddPage();
                        $pdf->SetFont('Arial','B',14); 
                        self::colocarQR($pdf,$x,$y,$codigo);
                    }else if ($p == 1){
                        self::colocarQR($pdf,$x*11,$y,$codigo);
                        $pdf->Ln($esp);
                    }else if ($p == 2){
                        self::colocarQR($pdf,$x,$y+$esp+10,$codigo);
                    }else if ($p == 3){
                        self::colocarQR($pdf,$x*11,$y+$esp+10,$codigo);
                    }
                    $i = $i + 1;
                    /*
                    $pdf->Cell(40,80,$codigo);
                    $pdf->Image($codigo,10,8,33);
                    */                   
                }
                 $ruta = self::getDireccionAbsoluta()."/web/archivos/pdfs/";
                  if($kernel->getEnvironment() === "dev"){
                        $ruta = \str_replace("/","\\",$ruta);
                    }
                $date_time = new \DateTime();
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
                 $peticion = $this->getRequest()->getSession();
                $id = $peticion->get('idprofesor');
                $em = $this->getDoctrine()->getManager();
                $asignaturas = $em->getRepository('SISigueBundle:ProfesorAsignatura')->findBy(array('idProfesor' => $id));
                //var_dump($asignaturas);
                $asig = array();
                foreach ($asignaturas as $a){
                    $as = $em->getRepository('SISigueBundle:Asignaturas')->findBy(array('id' => $a->getIdAsignatura()));
                array_push($asig, $as);             
            }
                return $asig;
            }
            
            private function colocarQR($pdf,$x,$y,$codigo){
                $pdf->Cell(100,10,'logo');
                $pdf->Image($codigo,$x,$y,80,80);
            }
    }
  
?>
