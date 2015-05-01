<?php

namespace TableTennis\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction()
    {
        return $this->render('TableTennisMainBundle:Default:index.html.twig', array());
    }
}
