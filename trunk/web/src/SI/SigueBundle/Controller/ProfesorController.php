<?php

namespace SI\SigueBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProfesorController extends Controller
{
    public function perfilAction()
    {
        return $this->render('SISigueBundle:Profesor:index.html.php');
    }

}
?>
