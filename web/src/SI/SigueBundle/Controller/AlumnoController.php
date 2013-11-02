<?php

namespace SI\SigueBundle\Controller;

use SI\SigueBundle\Entity\Alumno;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AlumnoController extends Controller
{
    public function perfilAction()
    {  
        $peticion = $this->getRequest();
        $alumno = $peticion->get('alumno');
        
        return $this->render('SISigueBundle:Alumno:perfil.html.php',array('alumno' => $alumno));
    }

}
?>
