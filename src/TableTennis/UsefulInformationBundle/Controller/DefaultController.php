<?php

namespace TableTennis\UsefulInformationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

class DefaultController extends Controller
{
    public function indexAction()
    {


    }
    
    public function showAction($slug)
    {
        $us_infos = $this->getDoctrine()
                         ->getRepository('TableTennisUsefulInformationBundle:UsefulInformation')
                         ->getCurrentUsefulInformation($slug)
                         ->getSingleResult();

        $media_formats = $this->get('sonata.media.pool')->getFormatNamesByContext('usefulInformation');

        /* Breadcrumbs */
        $breadcrumbs = $this->get( 'white_october_breadcrumbs' );
        $breadcrumbs->addItem( 'Accueil', $this->get("router")->generate( '_homepage' ));
        //$breadcrumbs->addItem( 'Informations utiles', $this->get("router")->generate( '_tableTennisUsefulInformationIndex' ));
        $breadcrumbs->addItem( $us_infos['title'] );

        return $this->render('TableTennisUsefulInformationBundle:Default:show.html.twig', array( 'us_infos' => $us_infos,
                                                                                                 'media_formats' => $media_formats ));
    }

    /**
     * @Cache(expires="tomorrow")
     */
    public function homeAction()
    {
        $useful_info_list = $this->getDoctrine()
                          ->getRepository('TableTennisUsefulInformationBundle:UsefulInformation')
                          ->findAllOrderedByUpdatedAt(5);

        return $this->render('TableTennisUsefulInformationBundle:Default:home.html.twig', array( 'useful_info_list' => $useful_info_list ));
    }
}
