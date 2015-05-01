<?php

// src\Base\ForumBundle\Controller\ForumController.php
namespace Base\ForumBundle\Controller;

use Herzult\Bundle\ForumBundle\Controller\ForumController as HerzultForumController;

class ForumController extends HerzultForumController
{
    public function indexAction()
    {
        /* Breadcrumbs */
        $breadcrumbs = $this->get( 'white_october_breadcrumbs' );
        $breadcrumbs->addItem( 'Accueil', $this->get('router')->generate( '_homepage' ));
        $breadcrumbs->addItem( 'Forum' );

        $response = parent::indexAction();

        return $response;
    }

    public function searchAction()
    {
        /* Breadcrumbs */
        $breadcrumbs = $this->get( 'white_october_breadcrumbs' );
        $breadcrumbs->addItem( 'Accueil', $this->get('router')->generate( '_homepage' ));
        $breadcrumbs->addItem( 'Forum', $this->get('router')->generate( 'herzult_forum_index' ));
        $breadcrumbs->addItem( 'Recherche' );

        $response = parent::searchAction();

        return $response;
    }
}

?>