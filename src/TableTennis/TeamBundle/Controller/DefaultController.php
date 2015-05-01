<?php

namespace TableTennis\TeamBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{

    public function indexAction()
    {
        // Cache
        $response = new Response();
        $response->setMaxAge(24*60*60);
        $response->setSharedMaxAge(24*60*60);
        $response->setPublic();

        if ($response->isNotModified($this->getRequest())) {
            return $response; // this will return the 304 if the cache is OK
        }
        
        $teams_list = $this->getDoctrine()
                     ->getRepository('TableTennisTeamBundle:Team')
                     ->getTeams()
                     ->getArrayResult();

        $media_formats = $this->get('sonata.media.pool')->getFormatNamesByContext('team');

        /* Breadcrumbs */
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Accueil", $this->get("router")->generate("_homepage"));
        $breadcrumbs->addItem("Liste des équipes du club");
        
        return $this->render('TableTennisTeamBundle:Default:index.html.twig', array( 'teams_list' => $teams_list,
                                                                                     'media_formats' => $media_formats ));
    }
}