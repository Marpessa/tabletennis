<?php

namespace TableTennis\PartnerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

/**
 * @Cache(expires="+2 days")
 */
class DefaultController extends Controller
{

    public function indexAction()
    {
        $partner_list = $this->getDoctrine()
                             ->getRepository('TableTennisPartnerBundle:Partner')
                             ->getPartners()
                             ->getArrayResult();

        shuffle( $partner_list );

        $media_formats = $this->get('sonata.media.pool')->getFormatNamesByContext('partner');
        
        /* Breadcrumbs */
        $breadcrumbs = $this->get( 'white_october_breadcrumbs' );
        $breadcrumbs->addItem( 'Accueil', $this->get("router")->generate( '_homepage' ));
        $breadcrumbs->addItem( 'Partenaires du club' );
        
        return $this->render('TableTennisPartnerBundle:Default:index.html.twig', array( 'partner_list' => $partner_list,
                                                                                        'media_formats' => $media_formats ));
    }

    public function showAction( $slug )
    {
        // Cache
        $response = new Response();
        $response->setMaxAge(24*60*60);
        $response->setSharedMaxAge(24*60*60);
        $response->setPublic();

        if ($response->isNotModified($this->getRequest())) {
            return $response; // this will return the 304 if the cache is OK
        }
        
        $partner = $this->getDoctrine()
                        ->getRepository('TableTennisPartnerBundle:Partner')
                        ->getCurrentPartner($slug)
                        ->getSingleResult();

        $media_formats = $this->get('sonata.media.pool')->getFormatNamesByContext('partner');
        
        /* Breadcrumbs */
        $breadcrumbs = $this->get( 'white_october_breadcrumbs' );
        $breadcrumbs->addItem( 'Accueil', $this->get("router")->generate( '_homepage' ));
        $breadcrumbs->addItem( 'Partenaires du club', $this->get("router")->generate( '_tableTennisPartnerIndex' ) );
        $breadcrumbs->addItem( $partner['title'] );
        
        return $this->render('TableTennisPartnerBundle:Default:show.html.twig', array( 'partner' => $partner,
                                                                                       'media_formats' => $media_formats ));
    }

    public function homeAction()
    {
        // Cache
        $response = new Response();
        $response->setMaxAge(24*60*60);
        $response->setSharedMaxAge(24*60*60);
        $response->setPublic();

        if ($response->isNotModified($this->getRequest())) {
            return $response; // this will return the 304 if the cache is OK
        }
        
        $partner_list = $this->getDoctrine()
                             ->getRepository('TableTennisPartnerBundle:Partner')
                             ->getPartners()
                             ->getArrayResult();

        shuffle( $partner_list );

        $media_formats = $this->get('sonata.media.pool')->getFormatNamesByContext('partner');
        
        return $this->render('TableTennisPartnerBundle:Default:home.html.twig', array( 'partner_list' => $partner_list,
                                                                                       'media_formats' => $media_formats ));
    }
}