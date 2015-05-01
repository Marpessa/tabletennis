<?php

namespace Base\PicsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

use Base\PicsBundle\Form\PictureForm;

class DefaultController extends Controller
{

    public function indexAction()
    {        
        $album_list = array();
        $album_list_json = array();

        try
        {
            $user_id = $this->container->getParameter('flickr_api.user_id');
            $api_key = $this->container->getParameter('flickr_api.api_key');

            $uri = 'https://api.flickr.com/services/rest/?method=flickr.photosets.getList&api_key=' . $api_key . '&user_id=' . $user_id . '&format=rest';
            $album_list = simplexml_load_file( $uri, "SimpleXMLElement", LIBXML_NOCDATA );

            $json = json_encode( $album_list->photosets );
            $album_list = json_decode($json, true);
            
            foreach($album_list["photoset"] as $album ){
                $album_list_json[] = array( 'attributes' => $album["@attributes"],
                                            'title' => $album["title"],
                                            'slug' => $this->slugify( $album["title"] ),
                                            'description' => $album["description"] );
            }

        }catch(Exception $e){

        }

        /* Breadcrumbs */
        $breadcrumbs = $this->get( 'white_october_breadcrumbs' );
        $breadcrumbs->addItem( 'Accueil', $this->get("router")->generate( '_homepage' ));
        $breadcrumbs->addItem( 'Galerie photos' );

        return $this->render('BasePicsBundle:Default:index.html.twig', array( 'album_list' => $album_list_json ));
    }

    public function showAction($id_album, $slug)
    {
        // Cache
        $response = new Response();
        $response->setMaxAge(24*60*60);
        $response->setSharedMaxAge(24*60*60);
        $response->setPublic();
        
        if ($response->isNotModified($this->getRequest())) {
            return $response; // this will return the 304 if the cache is OK
        }
        
        $photos_list = array();
        $photos_list_json = array();

        try
        {
            $user_id = $this->container->getParameter('flickr_api.user_id');
            $api_key = $this->container->getParameter('flickr_api.api_key');

            $uri = 'https://api.flickr.com/services/rest/?method=flickr.photosets.getPhotos&photoset_id=' . $id_album . '&api_key=' . $api_key . '&user_id=' . $user_id . '&format=rest';
            $photos_list = simplexml_load_file( $uri, "SimpleXMLElement", LIBXML_NOCDATA );

            $json = json_encode( $photos_list->photoset );
            $photos_list = json_decode($json, true);

            foreach($photos_list["photo"] as $photo ){
                $photos_list_json[] =  $photo["@attributes"];
            }

        }catch(Exception $e){

        }

        try
        {
            $uri = 'https://api.flickr.com/services/rest/?method=flickr.photosets.getInfo&photoset_id=' . $id_album . '&api_key=' . $api_key . '&user_id=' . $user_id . '&format=rest';
            $album_info = simplexml_load_file( $uri, "SimpleXMLElement", LIBXML_NOCDATA );

        }catch(Exception $e){

        }


        /* Breadcrumbs */
        $breadcrumbs = $this->get( 'white_october_breadcrumbs' );
        $breadcrumbs->addItem( 'Accueil', $this->get("router")->generate( '_homepage' ));
        $breadcrumbs->addItem( 'Galerie photos', $this->get("router")->generate( '_basePicsIndex' ));
        $breadcrumbs->addItem( $album_info->photoset->title );

        return $this->render('BasePicsBundle:Default:show.html.twig', array( 'album_info' => $album_info->photoset, 'photos_list' => $photos_list_json ), $response);
    }

    /**
     * @Cache(expires="tomorrow")
     */
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
        
        $photos_list = array();
        $photos_list_json = array();

        try
        {
            $user_id = $this->container->getParameter('flickr_api.user_id');
            $api_key = $this->container->getParameter('flickr_api.api_key');

            $uri = 'https://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=' . $api_key . '&user_id=' . $user_id . '&format=rest&per_page=50&page=1';
            
            $photos_list = simplexml_load_file( $uri, "SimpleXMLElement", LIBXML_NOCDATA );

            $json = json_encode( $photos_list->photos );
            $photos_list = json_decode($json, true);
            
            foreach($photos_list["photo"] as $photo ){
                $photos_list_json[] =  $photo["@attributes"];
            }

        }catch(Exception $e){

        }

        shuffle( $photos_list_json );
        $photos_list_json = array_slice( $photos_list_json, 0, 8 );
            
        
        return $this->render('BasePicsBundle:Default:home.html.twig', array( 'photos_list' => $photos_list_json ), $response);
    }
    
    public function postPhotoAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof \Application\Sonata\UserBundle\Entity\User ) {
            return $this->redirect( $this->generateUrl('fos_user_security_login') );
        }
        
        // Cache
        $response = new Response();
        
        $form = $this->createForm( new PictureForm() );
        
        $request = $this->getRequest();

        if ($request->isMethod('POST')) {
            $form->submit($request->request->get($form->getName()));

            if ($form->isValid()) {
                $media = new \Application\Sonata\MediaBundle\Entity\Media;
                $media->setProviderName('sonata.media.provider.image');
                $media->setContext('photo_gallery');

                $data = $form->getData();

                $file =$this->get('request')->files->get('base_pics_picture[media_id][binaryContent]');

                $media->setBinaryContent( $file );
                $media->setName( $user->getUsername() );
                $media->setProviderStatus( "" );
                $media->setProviderReference( "" );
                $media->setEnabled( TRUE );

                $mediaManager = $this->container->get('sonata.media.manager.media');
                $mediaManager->save($media);
                
                $message = \Swift_Message::newInstance()
                    ->setSubject("Publication d'une photo")
                    ->setFrom( $this->container->getParameter('base_contact.emails.contact_email_from') )
                    ->setTo( $this->container->getParameter('base_contact.emails.contact_email_to') )
                    ->setBody( $this->renderView('BasePicsBundle:Email:postPhotoEmail.txt.twig', array()) );
                
                $this->get('mailer')->send($message);

                $request->getSession()->getFlashBag()->add('flash-notice', 'Votre photo a bien été soumise. Après vérification, votre photo sera publiée sur le site.');

                return $this->redirect( $this->generateUrl('_basePicsPostPhoto') );
            }
        }
        
        /* Breadcrumbs */
        $breadcrumbs = $this->get( 'white_october_breadcrumbs' );
        $breadcrumbs->addItem( 'Accueil', $this->get("router")->generate( '_homepage' ));
        $breadcrumbs->addItem( 'Galerie photos', $this->get("router")->generate( '_basePicsIndex' ));
        $breadcrumbs->addItem( 'Publier une photo' );
        
        return $this->render('BasePicsBundle:Default:postPhoto.html.twig', array( 'form' => $form->createView() ), $response);
    }

    private function slugify($text) {
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
        $text = trim($text, '-');
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = strtolower($text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        
        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }
}

?>