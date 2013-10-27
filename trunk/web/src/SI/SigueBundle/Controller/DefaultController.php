<?php

namespace SI\SigueBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SISigueBundle:Default:index.html.php');
    }
}
