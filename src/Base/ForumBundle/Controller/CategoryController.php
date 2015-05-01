<?php

// src\Base\ForumBundle\Controller\CategoryController.php
namespace Base\ForumBundle\Controller;

use Herzult\Bundle\ForumBundle\Controller\CategoryController as HerzultCategoryController;

class CategoryController extends HerzultCategoryController
{
    /*public function listAction()
    {
        $response = parent::listAction();

        return $response;
    }*/

    public function showAction($slug)
    {
        $category = $this->get('herzult_forum.repository.category')->findOneBySlug($slug);

        if (!$category) {
            throw new NotFoundHttpException(sprintf('The category %s does not exist.', $slug));
        }

        $template = sprintf('%s:show.%s.%s', $this->container->getParameter('herzult_forum.templating.location.category'), $this->get('request')->getRequestFormat(), $this->getRenderer());

        /* Breadcrumbs */
        $breadcrumbs = $this->get( 'white_october_breadcrumbs' );
        $breadcrumbs->addItem( 'Accueil', $this->get('router')->generate( '_homepage' ));
        $breadcrumbs->addItem( 'Forum', $this->get('router')->generate( 'herzult_forum_index' ));
        $breadcrumbs->addItem( 'Liste des sujets de ' . $category->getName() );

        return $this->get('templating')->renderResponse($template, array(
            'category'  => $category,
            'page'      => $this->get('request')->query->get('page', 1)
        ));
    }

    public function topicNewAction($slug)
    {
        $response = parent::topicNewAction($slug);

        return $response;
    }

    public function topicCreateAction($slug)
    {
        $response = parent::topicCreateAction($slug);

        return $response;
    }
}
