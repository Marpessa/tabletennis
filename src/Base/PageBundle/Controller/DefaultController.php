<?php

namespace Base\PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

/**
 * @Cache(expires="tomorrow")
 */
class DefaultController extends Controller
{

    public function indexAction($slug)
    {
        $page = $this->getDoctrine()
                   ->getRepository('BasePageBundle:Page')
                   ->getCurrentPage($slug)
                   ->getSingleResult();

        /* Breadcrumbs */
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Accueil", $this->get("router")->generate("_homepage"));
        $breadcrumbs->addItem( $page['title'] );

        return $this->render('BasePageBundle:Default:index.html.twig', array('page' => $page));
    }
}
