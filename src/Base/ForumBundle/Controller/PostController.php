<?php

// src\Base\ForumBundle\Controller\PostController.php
namespace Base\ForumBundle\Controller;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Herzult\Bundle\ForumBundle\Controller\PostController as HerzultPostController;

class PostController extends HerzultPostController
{
    public function createAction($categorySlug, $slug)
    {
        $topic = $this->findTopicOr404($categorySlug, $slug);
        $form  = $this->get('herzult_forum.form.post');
        $post  = $this->get('herzult_forum.repository.post')->createNewPost();
        $post->setTopic($topic);

        $request = $this->getRequest();
        $form->submit($request->request->get($form->getName()));

        if (!$form->isValid()) {
            $template = sprintf('%s:new.html.%s', $this->container->getParameter('herzult_forum.templating.location.post'), $this->getRenderer());
            return $this->get('templating')->renderResponse('HerzultForumBundle:Post:new.html.'.$this->getRenderer(), array(
                'form'  => $form->createView(),
                'topic' => $topic,
            ));
        }

        $post = $form->getData();
        $post->setTopic($topic);
        $this->get('herzult_forum.creator.post')->create($post);
        $this->get('herzult_forum.blamer.post')->blame($post);

        $objectManager = $this->get('herzult_forum.object_manager');
        $objectManager->persist($post);
        $objectManager->flush();

        $request->getSession()->getFlashBag()->add('herzult_forum_post_create/success', true);
        $url = $this->get('herzult_forum.router.url_generator')->urlForPost($post);

        return new RedirectResponse($url);
    }

    public function newAction($categorySlug, $slug)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof \Application\Sonata\UserBundle\Entity\User ) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        
        $topic    = $this->findTopicOr404($categorySlug, $slug);
        $form     = $this->get('herzult_forum.form.post');
        $template = sprintf(
            '%s:new.html.%s',
            $this->container->getParameter('herzult_forum.templating.location.post'),
            $this->getRenderer()
        );
        
        /* Breadcrumbs */
        $breadcrumbs = $this->get( 'white_october_breadcrumbs' );
        $breadcrumbs->addItem( 'Accueil', $this->get('router')->generate( '_homepage' ));
        $breadcrumbs->addItem( 'Forum', $this->get('router')->generate( 'herzult_forum_index' ));
        $breadcrumbs->addItem( $topic->getSubject(), $this->get('router')->generate( 'herzult_forum_topic_show', array( 'timestamp' => $topic->getCreatedAt()->format('U'), 'categorySlug' => $categorySlug, 'slug' => $slug ) ) );
        $breadcrumbs->addItem( 'Déposer un message' );

        return $this->render(
            $template,
            array(
                'form'  => $form->createView(),
                'topic' => $topic,
            )
        );
    }
    /*
    public function createAction($categorySlug, $slug)
    {
        $response = parent::createAction($categorySlug, $slug);

        return $response;
    }*/

    public function deleteAction($id)
    {
        $response = parent::deleteAction($id);

        return $response;
    }

    /*protected function findTopicOr404($categorySlug, $slug)
    {
        $response = parent::findTopicOr404($categorySlug, $slug);

        return $response;
    }*/
}
