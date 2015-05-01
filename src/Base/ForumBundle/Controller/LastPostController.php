<?php

// src\Base\ForumBundle\Controller\PostController.php
namespace Base\ForumBundle\Controller;

use Herzult\Bundle\ForumBundle\Controller\PostController as HerzultPostController;

use Symfony\Component\HttpFoundation\Response;

class LastPostController extends HerzultPostController
{
    public function showAction()
    {
        $last_post_list = $this->getDoctrine()
                               ->getRepository('BaseForumBundle:Post')
                               ->findLastPost( 3 )
                               ->getArrayResult();
        
        $first_post = reset( $last_post_list );
        
        // Cache
        $response = new Response();
        $response->setEtag( md5( $first_post['id'] ) );
        $response->setMaxAge(24*60*60);
        $response->setSharedMaxAge(24*60*60);
        $response->setPublic();

        if ($response->isNotModified($this->getRequest())) {
            return $response; // this will return the 304 if the cache is OK
        }
         
        return $this->render('BaseForumBundle:Post:last_post_home.html.twig', array( 'last_post_list' => $last_post_list ),
                             $response );
    }
}