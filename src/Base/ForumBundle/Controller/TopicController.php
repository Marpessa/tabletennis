<?php

// src\Base\ForumBundle\Controller\TopicController.php
namespace Base\ForumBundle\Controller;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Herzult\Bundle\ForumBundle\Model\Category;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;

use Herzult\Bundle\ForumBundle\Controller\TopicController as HerzultTopicController;

class TopicController extends HerzultTopicController
{
    public function newAction(Category $category = null)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof \Application\Sonata\UserBundle\Entity\User ) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $form = $this->get('herzult_forum.form.new_topic');
        $topic = $this->get('herzult_forum.repository.topic')->createNewTopic();
        if ($category) {
            $topic->setCategory($category);
        }
        $form->setData($topic);

        /* Breadcrumbs */
        $breadcrumbs = $this->get( 'white_october_breadcrumbs' );
        $breadcrumbs->addItem( 'Accueil', $this->get('router')->generate( '_homepage' ));
        $breadcrumbs->addItem( 'Forum', $this->get('router')->generate( 'herzult_forum_index' ));
        $breadcrumbs->addItem( $topic->getCategory(), $this->get('router')->generate( 'herzult_forum_category_show', array( 'slug' => $category->getSlug() ) ) );
        $breadcrumbs->addItem( 'Créer un nouveau sujet de discussion' );

        $template = sprintf('%s:new.html.%s', $this->container->getParameter('herzult_forum.templating.location.topic'), $this->getRenderer());
        return $this->get('templating')->renderResponse($template, array(
            'form'      => $form->createView(),
            'category'  => $category
        ));
    }

    public function createAction(Category $category = null)
    {
        $form = $this->get('herzult_forum.form.new_topic');

        $request = $this->getRequest();
        $form->submit($request->request->get($form->getName()));
        $topic = $form->getData();

        if (!$form->isValid()) {
            $template = sprintf('%s:new.html.%s', $this->container->getParameter('herzult_forum.templating.location.topic'), $this->getRenderer());
            return $this->get('templating')->renderResponse('HerzultForumBundle:Topic:new.html.'.$this->getRenderer(), array(
                'form'      => $form->createView(),
                'category'  => $category
            ));
        }

        $this->get('herzult_forum.creator.topic')->create($topic);
        $this->get('herzult_forum.blamer.topic')->blame($topic);

        $this->get('herzult_forum.creator.post')->create($topic->getFirstPost());
        $this->get('herzult_forum.blamer.post')->blame($topic->getFirstPost());

        $objectManager = $this->get('herzult_forum.object_manager');
        $objectManager->persist($topic);
        $objectManager->persist($topic->getFirstPost());
        $objectManager->flush();

        $request->getSession()->getFlashBag()->add('herzult_forum_topic_create/success', true);
        $url = $this->get('herzult_forum.router.url_generator')->urlForTopic($topic);

        return new RedirectResponse($url);
    }

    public function listAction($categorySlug, array $pagerOptions)
    {
        if (null === $categorySlug) {
            $category = null;
            $topics   = $this->get('herzult_forum.repository.topic')->findAll(true);
        } else {
            $category = $this->findCategoryOr404($categorySlug);
            $topics   = $this->get('herzult_forum.repository.topic')->findAllByCategory($category, true);
        }

        $topics->setCurrentPage($pagerOptions['page']);
        $topics->setMaxPerPage($this->container->getParameter('herzult_forum.paginator.topics_per_page'));

        $template = sprintf('%s:list.%s.%s', $this->container->getParameter('herzult_forum.templating.location.topic'), $this->get('request')->getRequestFormat(), $this->getRenderer());
        return $this->get('templating')->renderResponse($template, array(
            'topics'    => $topics,
            'category'  => $category,
            'pagerOptions' => $pagerOptions
        ));
    }

    public function indexAction($timestamp, $categorySlug, $slug)
    {
        $topic = $this->findCurrentTopic($timestamp, $categorySlug, $slug);
        $this->get('herzult_forum.repository.topic')->incrementTopicNumViews($topic);

        if ('html' === $this->get('request')->getRequestFormat()) {
            $page = $this->get('request')->query->get('page', 1);
            $posts = $this->get('herzult_forum.repository.post')
                          ->findAllByTopic($topic)
                          ->getArrayResult();

            //$posts = new Pagerfanta(new ArrayAdapter($posts));

            //$posts->setCurrentPage($page);
            //$posts->setMaxPerPage($this->container->getParameter('herzult_forum.paginator.posts_per_page'));
        } else {
            $posts = $this->get('herzult_forum.repository.post')->findRecentByTopic($topic, 30);
        }

        $template = sprintf('%s:index.%s.%s', $this->container->getParameter('herzult_forum.templating.location.topic'), $this->get('request')->getRequestFormat(), $this->getRenderer());

        /* Breadcrumbs */
        $breadcrumbs = $this->get( 'white_october_breadcrumbs' );
        $breadcrumbs->addItem( 'Accueil', $this->get('router')->generate( '_homepage' ));
        $breadcrumbs->addItem( 'Forum', $this->get('router')->generate( 'herzult_forum_index' ));
        $breadcrumbs->addItem( $topic->getCategory(), $this->get('router')->generate( 'herzult_forum_category_show', array( 'slug' => $categorySlug ) ) );
        $breadcrumbs->addItem( $topic->getSubject() );

        /* Media Format */
        $media_formats = $this->get('sonata.media.pool')->getFormatNamesByContext('avatar');

        return $this->get('templating')->renderResponse($template, array(
            'topic' => $topic,
            'posts' => $posts,
            'media_formats' => $media_formats
        ));
    }

    public function postNewAction($categorySlug, $slug)
    {
        $response = parent::postNewAction($categorySlug, $slug);

        return $response;
    }

    public function postCreateAction($categorySlug, $slug)
    {
        $response = parent::postCreateAction($categorySlug, $slug);

        return $response;
    }

    public function deleteAction($id)
    {
        $response = parent::deleteAction($id);

        return $response;
    }

    /**
     * Find a topic by its category slug and topic slug
     *
     * @return Topic
     **/
    public function findCurrentTopic($timestamp, $categorySlug, $topicSlug)
    {
        $category = $this->get('herzult_forum.repository.category')->findOneBySlug($categorySlug);
        if (!$category) {
            throw new NotFoundHttpException(sprintf('The category with slug "%s" does not exist', $categorySlug));
        }
        
        $topic = $this->get('herzult_forum.repository.topic')->findOneByTimeStampAndCategoryAndSlug($timestamp, $category, $topicSlug);
        if (!$topic) {
            throw new NotFoundHttpException(sprintf('The topic with slug "%s" does not exist', $topicSlug));
        }

        return $topic;
    }
}
