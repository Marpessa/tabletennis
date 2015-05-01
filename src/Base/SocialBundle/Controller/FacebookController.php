<?php

namespace Base\SocialBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FacebookController extends Controller
{
    public function likeboxAction()
    {
        return $this->render('BaseSocialBundle:Facebook:likebox.html.twig', array());
    }

    public function likebuttonAction()
    {
    	$current_uri = $_SERVER['REQUEST_URI'];
        return $this->render('BaseSocialBundle:Facebook:likebutton.html.twig', array( "current_uri" => $current_uri ));
    }
}

?>