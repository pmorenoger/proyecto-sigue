<?php

namespace SI\SigueBundle\Controller;


use SI\SigueBundle\Entity\Alumno;
use SI\SigueBundle\Entity\Profesor;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProfesorController extends Controller
    {
        public function indexAction()
        {
           return $this->render('SISigueBundle:Profesor:index.html.php');
        }
        public function subir_alumnoAction(){
            /*PARA SUBIR UNA LISTA EXCEL DE ALUMNOS, HAY QUE:
             * 1º PROCESAR EL FICHERO
             * 2º GUARDAR CADA FILA EN LA TABLA ALUMNOS (con la entity Alumno)
             * 3º GUARDAR EL COMBO PROFESOR-ASIGNATURA
             * 
             */
            
            return $this->render('SISigueBundle:Profesor:index.html.php', array("exito"=>"true"));
        }
        
        
        public function subir_alumno($fila)
            {
                $alumno = new Alumno();
                $alumno->setNombre("Diego");
                $alumno->setDni("70820622B");
                $alumno->setApellidos(' es el puto amo');
                $alumno->setCorreo("diego.santos.garcia@ucm.es");
                $alumno->setPassword("diego");

                $em = $this->getDoctrine()->getManager();
                $em->persist($alumno);
                $em->flush();

                return $this->render('SISigueBundle:Default:prueba.html.php', array("idalumno"=>$alumno->getIdalumno()));
            }
    }
?>
