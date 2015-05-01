<?php

namespace TableTennis\MatchTypeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{

    public function indexAction()
    {
        $em = $this->container->get('doctrine')->getManager();
	    $matchType_list = $em->getRepository('TableTennisMatchTypeBundle:MatchType')->findAll();

        /* Breadcrumbs */
        $breadcrumbs = $this->get( 'white_october_breadcrumbs' );
        $breadcrumbs->addItem( 'Accueil', $this->get("router")->generate( '_homepage' ));
        $breadcrumbs->addItem( 'Coëfficients des compétitions' );
        
        return $this->render('TableTennisMatchTypeBundle:Default:index.html.twig', array('matchType_list' => $matchType_list));
    }
}