<?php

namespace Base\EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{

    public function indexAction()
    {
        $event_list = $this->getDoctrine()
                           ->getRepository('BaseEventBundle:Event')
                           ->getEvents()
                           ->getArrayResult();

        /* Breadcrumbs */
        $breadcrumbs = $this->get( 'white_october_breadcrumbs' );
        $breadcrumbs->addItem( 'Accueil', $this->get("router")->generate( '_homepage' ));
        $breadcrumbs->addItem( 'Evénements' );

        return $this->render('BaseEventBundle:Default:index.html.twig', array( 'event_list' => $event_list ));
    }

    public function showAction($slug)
    {
        $event = $this->getDoctrine()
                      ->getRepository('BaseEventBundle:Event')
                      ->getCurrentEvent($slug)
                      ->getSingleResult();

        $related_events_list = $this->getDoctrine()
                                    ->getRepository('BaseEventBundle:Event')
                                    ->getRelatedEvents($slug)
                                    ->getArrayResult();

        $media_formats = $this->get('sonata.media.pool')->getFormatNamesByContext('event');

        /* Breadcrumbs */
        $breadcrumbs = $this->get( 'white_october_breadcrumbs' );
        $breadcrumbs->addItem( 'Accueil', $this->get("router")->generate( '_homepage' ));
        $breadcrumbs->addItem( 'Evénements', $this->get("router")->generate( '_baseEventIndex' ));
        $breadcrumbs->addItem( $event['title'] );

        return $this->render('BaseEventBundle:Default:show.html.twig', array( 'event' => $event,
                                                                              'related_events_list' => $related_events_list,
                                                                              'media_formats' => $media_formats ));
    }

    /*public function homeAction()
    {
        $event_list = $this->getDoctrine()
                           ->getRepository('BaseEventBundle:Event')
                           ->findAllOrderedByUpdatedAt(3);
        
        return $this->render('BaseEventBundle:Default:home.html.twig', array( 'event_list' => $event_list ));
    }*/
}

?>