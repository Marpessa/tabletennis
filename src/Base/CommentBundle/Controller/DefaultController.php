<?php

namespace Base\CommentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Base\CommentBundle\Entity\Comment;
use Base\CommentBundle\Form\CommentForm;
use Base\CommentBundle\Form\CommentUserLoggedForm;

use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function commonAction()
    {
        $comment_list = $this->getDoctrine()
                             ->getRepository('BaseCommentBundle:Comment')
                             ->getComments( $_SERVER['REQUEST_URI'] )
                             ->getArrayResult();

        $commentEntity = new Comment();
        $commentEntity->setLink( $_SERVER['REQUEST_URI'] );

        $securityContext = $this->container->get('security.context');

        if( $securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED') ){
            $form = $this->createForm(new CommentUserLoggedForm(), $commentEntity );
        }else{
            $form = $this->createForm(new CommentForm(), $commentEntity );
        }

        $media_formats = $this->get('sonata.media.pool')->getFormatNamesByContext('avatar');

        return $this->render('BaseCommentBundle:Default:common.html.twig', array( 'form' => $form->createView(),
                                                                                  'comment_list' => $comment_list,
                                                                                  'media_formats' => $media_formats
                                                                                ) );
    }

    public function indexAction()
    {
        $comment_list = $this->getDoctrine()
                             ->getRepository('BaseCommentBundle:Comment')
                             ->getLastComments(30)
                             ->getArrayResult();
        
        /* Breadcrumbs */
        $breadcrumbs = $this->get( 'white_october_breadcrumbs' );
        $breadcrumbs->addItem( 'Accueil', $this->get("router")->generate( '_homepage' ));
        $breadcrumbs->addItem( 'Derniers commentaires' );

        return $this->render('BaseCommentBundle:Default:index.html.twig', array('comment_list' => $comment_list));
    }

    public function homeAction()
    {
        $comment_list = $this->getDoctrine()
                             ->getRepository('BaseCommentBundle:Comment')
                             ->getLastComments(3)
                             ->getArrayResult();
        
        /*$first_comment = reset( $comment_list );
        
         // Cache
        $response = new Response();
        $response->setEtag( md5( $first_comment[ 'id' ] ) );
        $response->setMaxAge(24*60*60);
        $response->setSharedMaxAge(24*60*60);
        $response->setPublic();

        if ($response->isNotModified($this->getRequest())) {
            return $response; // this will return the 304 if the cache is OK
        }*/
        
        return $this->render('BaseCommentBundle:Default:home.html.twig', array('comment_list' => $comment_list));
    }

    public function validAction()
    {
        $commentEntity = new Comment();
        $commentEntity->setIsPublished( true );

        $securityContext = $this->container->get('security.context');

        if( $securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED') ){
            $user = $this->get('security.context')->getToken()->getUser();
            $commentEntity->setUserId( $user );
            $commentEntity->setName( $user->getUsername() );
            $commentEntity->setEmail( $user->getEmail() );

            $form = $this->createForm(new CommentUserLoggedForm(), $commentEntity );
        }else{
            $form = $this->createForm(new CommentForm(), $commentEntity );
        }
        
        $request = $this->getRequest();

        if ($request->isMethod('POST')) {

            $form->submit($request->request->get($form->getName()));
            
            $results = $request->request->get('comment');
            
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist( $commentEntity );
                $em->flush();

                $request->getSession()->getFlashBag()->add('flash-notice', 'Votre message a bien été publié. Merci.');

                return $this->redirect( $results["link"] . "#comment_" . $commentEntity->getId() );
            }
            else
            {
                $errors = array();
                foreach ($form->all() as $key => $child) {
                    $errorsChild = $child->getErrors();
                    foreach( $errorsChild as $error ) {
                        $errors[] = $error->getMessage();
                    }
                }

                $request->getSession()->getFlashBag()->add('flash-error', $errors);
            }

            return $this->redirect( $results["link"] );
        }
        
        return $this->redirect( $this->get("router")->generate( '_homepage' ) );
    }
}
