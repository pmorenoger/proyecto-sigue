<?php

namespace SI\SigueBundle\Controller;



use SI\SigueBundle\Entity\Alumnos;
use SI\SigueBundle\Entity\Codigos;
use SI\SigueBundle\Entity\Asignaturas;
use SI\SigueBundle\Entity\ProfesorAsignatura;
use SI\SigueBundle\Entity\AsignaturaAlumno;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProfesorController extends Controller
    {
        public function indexAction($exito)
        {     
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
           if(!is_array($exito)){                           
               return $this->render('SISigueBundle:Profesor:index.html.php',array("exito" => $exito,'asignaturas' =>$asig));
           }else{
               array_push($exito,$asig);
               var_dump($exito);
               return $this->render('SISigueBundle:Profesor:index.html.php',$exito);
           }              
        }
        public function subir_alumnoAction(){
            
            $kernel = $this->get('kernel');
            $dir_abs = $kernel->getRootDir();
            //Aqui tenemos la direccion hasta app, hay que volver y paso, y aplicar vendor.
            $dir_abs = explode('/', $dir_abs);
            array_pop($dir_abs);
            $dir_abs = implode('/', $dir_abs);
            require_once $dir_abs . '/vendor/Excel/lib/src/Classes/PHPExcel.php';
            
             $request = Request::createFromGlobals();
            /*PARA SUBIR UNA LISTA EXCEL DE ALUMNOS, HAY QUE:
             * 1º PROCESAR EL FICHERO \/
             * 2º GUARDAR CADA FILA EN LA TABLA ALUMNOS (con la entity Alumno) \/
             * 3º GUARDAR EL COMBO PROFESOR-ASIGNATURA -- PENDIENTE!!!
             * 
             */
            
             $exito = "none";  
             if( $kernel->getEnvironment() === "dev" ){
                 /*Si quereis que funcione en vuestro desarrollo localhost cambiad esta ruta.*/
                $uploaddir = 'C:\Users\j\Documents\proyecto-sigue\web\web\archivos';
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
           $em->persist($asignatura_alumno);
        }
 
        public function generar_qrAction(){
                $request = Request::createFromGlobals();
                $cantidad = $request->request->get('cantidad');
                $id_asignatura = $request->request->get('id_asignatura');
                // var_dump($id_asignatura);
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
                    }else{
                        /*Ese codigo queda descartado*/
                        $i--;
                    }   
                }
                $em->flush();
                return $this->indexAction("true2");    
        }
        
        private function hashSSHA($password) {
        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;

    }
    }
?>
