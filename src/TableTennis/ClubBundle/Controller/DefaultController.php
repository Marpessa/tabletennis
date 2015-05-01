<?php

namespace TableTennis\ClubBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->container->get('doctrine')->getEntityManager();
	$club_list = $em->getRepository('TableTennisClubBundle:Club')->findAll();

        /* Breadcrumbs */
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Accueil", $this->get("router")->generate("_homepage"));
        $breadcrumbs->addItem("Liste des clubs de tennis de table");

        return $this->render('TableTennisClubBundle:Default:index.html.twig', array('club_list' => $club_list));
    }
}