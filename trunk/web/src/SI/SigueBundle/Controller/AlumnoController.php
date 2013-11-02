<?php

namespace SI\SigueBundle\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AlumnoController extends Controller
{
    public function perfilAction()
    {
        return $this->render('SISigueBundle:Alumno:perfil.html.php');
    }

}
?>
