<?php

namespace TableTennis\HomePageFrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction()
    {

        /* Breadcrumbs */
        $breadcrumbs = $this->get( 'white_october_breadcrumbs' );
        $breadcrumbs->addItem( 'Accueil', $this->get("router")->generate( '_homepage' ));

        $response = $this->render('TableTennisHomePageFrontendBundle:Default:index.html.twig', array());

        /*$response->setMaxAge(600);
        $response->setPublic();*/
        
        return $response;
    }
}
