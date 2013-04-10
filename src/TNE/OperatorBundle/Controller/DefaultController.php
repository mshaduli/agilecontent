<?php

namespace TNE\OperatorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('TNEOperatorBundle:Default:index.html.twig', array('name' => 'test'));
    }
}
