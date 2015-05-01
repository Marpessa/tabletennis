<?php

namespace Base\ContactBundle\Controller;

use Base\ContactBundle\Entity\Enquiry;
use Base\ContactBundle\Form\EnquiryType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{

    public function indexAction()
    {
        $enquiry = new Enquiry();
        $form = $this->createForm(new EnquiryType(), $enquiry);

        $request = $this->getRequest();
        if ($request->isMethod('POST')) {
            $form->submit($request->request->get($form->getName()));


            if ($form->isValid()) {
                
                $message = \Swift_Message::newInstance()
                    ->setSubject('Formulaire de contact')
                    ->setFrom( $this->container->getParameter('base_contact.emails.contact_email_from') )
                    ->setTo( $this->container->getParameter('base_contact.emails.contact_email_to') )
                    ->setBody( $this->renderView('BaseContactBundle:Email:contactEmail.txt.twig', array('enquiry' => $enquiry)) );
                
                $this->get('mailer')->send($message);

                $request->getSession()->getFlashBag()->add('flash-notice', 'Votre message a bien été envoyé. Merci.');

                // Redirect - This is important to prevent users re-posting
                // the form if they refresh the page
                return $this->redirect($this->generateUrl('_baseContactIndex'));
            }
        }

        $map = $this->getMap();

        /* Breadcrumbs */
        $breadcrumbs = $this->get( 'white_october_breadcrumbs' );
        $breadcrumbs->addItem( 'Accueil', $this->get("router")->generate( '_homepage' ));
        $breadcrumbs->addItem( 'Nous contacter' );

        return $this->render('BaseContactBundle:Default:index.html.twig', array( 'form' => $form->createView(), 
                                                                                 'map' => $map ));
    }

    private function getMap() {
        $map = $this->get('ivory_google_map.map');
        //$mapTypeControl = $this->get('ivory_google_map.map_type_control');
        //$zoomControl = $this->get('ivory_google_map.zoom_control');
        $marker = $this->get('ivory_google_map.marker');
        //$panControl = $this->get('ivory_google_map.pan_control');
        //$scaleControl = $this->get('ivory_google_map.scale_control');
        //$streetViewControl = $this->get('ivory_google_map.streetViewControl');
        //$infoWindow = $this->get('ivory_google_map.info_window');

        
        $map->addMarker($marker);
        //$map->addInfoWindow($infoWindow);
        //$map->setMapTypeControl($mapTypeControl);
        //$map->setZoomControl($zoomControl);
        //$map->setPanControl($panControl);
        //$map->setScaleControl($scaleControl);
        //$map->setStreetViewControl($streetViewControl);

        return $map;
    }
}

?>