<?php

namespace SI\SigueBundle\Controller;



use SI\SigueBundle\Entity\Alumnos;
use SI\SigueBundle\Entity\Profesor;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProfesorController extends Controller
    {
        public function indexAction($exito)
        {     
           if(!is_array($exito)){
               return $this->render('SISigueBundle:Profesor:index.html.php',array("exito" => $exito));
           }else{
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
             if( $kernel->isDebug() ){
                $uploaddir = 'K:/Users/loko64z/Desktop/Sistemas-Informaticos/proyecto-sigue/web/web/archivos/';
                $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
                //echo $uploadfile;
             }else{
                $uploaddir = '/home/administrador/web/web/archivos/';
                $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
                //echo $uploadfile;
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
                    
                    foreach ($objWorksheet->getRowIterator() as $row) {                        

                        $cellIterator = $row->getCellIterator();
                        $cellIterator->setIterateOnlyExistingCells(true); // This loops all cells iterated.                       
                        self::subir_alumno($cellIterator);                    
                    }

            
            return $this->render('SISigueBundle:Profesor:index.html.php',array("exito" => "true"));
        }
        
        
        public function subir_alumno($fila)
            {
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
                        /*
                          case 4:                        
                                $alumno->setPassword($celda->getValue());
                                break;
                         
                         */

                    }
                }else{
                    
                    return;
                }
                $pass_provisional = explode("@",$alumno->getCorreo());
                $alumno->setPassword($pass_provisional[0]);
                
            }
            var_dump($alumno);
                  //  $em = $this->getDoctrine()->getManager();
                  //  $em->persist($alumno);
                  //  $em->flush();
                
            }
    }
?>
