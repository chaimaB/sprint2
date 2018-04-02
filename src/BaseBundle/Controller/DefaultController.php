<?php

namespace BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BaseBundle:Default:index.html.twig');
    }

    public function dashAction()
    {
        return $this->render('BaseBundle:Default:dash.html.twig');
    }
}
