<?php

namespace TableTennis\ScoringTableBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{

    public function indexAction()
    {
        $em = $this->container->get('doctrine')->getManager();
	    $scoringTable_list = $em->getRepository('TableTennisScoringTableBundle:ScoringTable')->findAll();

        /* Breadcrumbs */
        $breadcrumbs = $this->get( 'white_october_breadcrumbs' );
        $breadcrumbs->addItem( 'Accueil', $this->get("router")->generate( '_homepage' ));
        $breadcrumbs->addItem( 'Barêmes des points de tennis de table' );

        return $this->render('TableTennisScoringTableBundle:Default:index.html.twig', array('scoringTable_list' => $scoringTable_list));
    }
}