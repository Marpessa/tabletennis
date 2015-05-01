<?php

namespace Base\GoogleMapBundle\Controller;

use Base\ContactBundle\Entity\Enquiry;
use Base\ContactBundle\Form\EnquiryType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{

    public function indexAction()
    {
        $map = $this->getMap();

             $enquiry = new Enquiry();
        $form = $this->createForm(new EnquiryType(), $enquiry);
        
        /* Breadcrumbs */
        $breadcrumbs = $this->get( 'white_october_breadcrumbs' );
        $breadcrumbs->addItem( 'Accueil', $this->get("router")->generate( '_homepage' ));
        $breadcrumbs->addItem( 'Nous trouver' );

        return $this->render('BaseGoogleMapBundle:Default:index.html.twig', array( 'form' => $form->createView(), 
                                                                                   'map' => $map ));
    }

    private function getMap() {
        $map = $this->get('ivory_google_map.map');
        $mapTypeControl = $this->get('ivory_google_map.map_type_control');
        $zoomControl = $this->get('ivory_google_map.zoom_control');
        $marker = $this->get('ivory_google_map.marker');
        $panControl = $this->get('ivory_google_map.pan_control');
        $scaleControl = $this->get('ivory_google_map.scale_control');
        $streetViewControl = $this->get('ivory_google_map.streetViewControl');
        //$infoWindow = $this->get('ivory_google_map.info_window');

        $map->setMapOption('scrollwheel', true);
        $map->setMapOption('draggable', true);

        $map->setStylesheetOption('height', '350px');
        
        $map->addMarker($marker);
        //$map->addInfoWindow($infoWindow);
        $map->setMapTypeControl($mapTypeControl);
        $map->setZoomControl($zoomControl);
        $map->setPanControl($panControl);
        $map->setScaleControl($scaleControl);
        $map->setStreetViewControl($streetViewControl);

        return $map;
    }
}
