<?php

namespace Base\NewsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

class DefaultController extends Controller
{

    public function indexAction()
    {
        $request = $this->getRequest();
        $page = $request->get('page', 1);

        $queryNewsList = $this->getDoctrine()
                         ->getRepository('BaseNewsBundle:News')
                         ->getNews();

                    //->getArrayResult();

        $adapter = new DoctrineORMAdapter( $queryNewsList );
        $news_list = new Pagerfanta( $adapter );

        $news_list->setMaxPerPage(10);

        try {
            $news_list->setCurrentPage($page);
        } catch(NotValidCurrentPageException $e) {
            throw new NotFoundHttpException();
        }

        /* Breadcrumbs */
        $breadcrumbs = $this->get( 'white_october_breadcrumbs' );
        $breadcrumbs->addItem( 'Accueil', $this->get("router")->generate( '_homepage' ));
        $breadcrumbs->addItem( 'Actualités' );

        return $this->render('BaseNewsBundle:Default:index.html.twig', array( 'news_list' => $news_list ));
    }

    public function showAction( $year, $month, $slug )
    {
        $news = $this->getDoctrine()
                     ->getRepository('BaseNewsBundle:News')
                     ->getCurrentNews($slug)
                     ->getSingleResult();

        $related_news_list = $this->getDoctrine()
                                  ->getRepository('BaseNewsBundle:News')
                                  ->getRelatedNews($slug, $news['c_id'])
                                  ->getArrayResult();

        $media_formats = $this->get('sonata.media.pool')->getFormatNamesByContext('news');

        /* Breadcrumbs */
        $breadcrumbs = $this->get( 'white_october_breadcrumbs' );
        $breadcrumbs->addItem( 'Accueil', $this->get("router")->generate( '_homepage' ));
        $breadcrumbs->addItem( 'Actualités', $this->get("router")->generate( '_baseNewsIndex' ));
        $breadcrumbs->addItem( $news['title'] );

        return $this->render('BaseNewsBundle:Default:show.html.twig', array( 'news' => $news,
                                                                             'related_news_list' => $related_news_list,
                                                                             'media_formats' => $media_formats ));
    }

    public function homeAction()
    {
        $news_list = $this->getDoctrine()
                          ->getRepository('BaseNewsBundle:News')
                          ->findAllOrderedByUpdatedAt(11);

        $first_news = reset( $news_list );

        // Cache
        $response = new Response();
        /*$response->setEtag( md5( $first_news[ 'title' ] . $first_news[ 'updatedAt' ] ) );
        $response->setLastModified( new \Datetime( $first_news[ 'updatedAt' ] ) );
        $response->setSharedMaxAge(24*60*60);
        $response->setPublic();

        if ($response->isNotModified($this->getRequest())) {
            return $response; // this will return the 304 if the cache is OK
        }*/

        //$media_formats = $this->get('sonata.media.pool')->getFormatNamesByContext('news');

        $news_list_big_highlight = array();
        $news_list_small_highlight = array();
        $i = 0;

        foreach($news_list as $news) {
            if($i >= 3) {
                $index_small = $i-3;
                $news_list_small_highlight[$index_small]['title'] = $news[ 'title' ];
                $news_list_small_highlight[$index_small]['content'] = $news[ 'content' ];
                $news_list_small_highlight[$index_small]['u_username'] = $news[ 'u_username' ];

                $news_list_small_highlight[$index_small]['m_id'] = $news[ 'm_id' ];
                $news_list_small_highlight[$index_small]['m_width'] = 700;
                $news_list_small_highlight[$index_small]['m_height'] = $news[ 'm_height' ];

                $news_list_small_highlight[$index_small]['slug'] = $news[ 'slug' ];
                $news_list_small_highlight[$index_small]['updatedAt'] = new \Datetime( $news[ 'updatedAt' ] );
                $news_list_small_highlight[$index_small]['is_new'] = strtotime( $news[ 'updatedAt' ] ) > ( time() - 5 * 24 * 60 * 60 );
            }
            $i++;
        }

        return $this->render('BaseNewsBundle:Default:home.html.twig', array('news_list_small_highlight' => $news_list_small_highlight ),
                             $response );
    }

    public function homeHighlightAction()
    {
        $news_list = $this->getDoctrine()
                          ->getRepository('BaseNewsBundle:News')
                          ->findAllOrderedByUpdatedAt(3);

        $first_news = reset( $news_list );

        // Cache
        $response = new Response();
        /*$response->setEtag( md5( $first_news[ 'title' ] . $first_news[ 'updatedAt' ] ) );
        $response->setLastModified( new \Datetime( $first_news[ 'updatedAt' ] ) );
        $response->setSharedMaxAge(24*60*60);
        $response->setPublic();

        if ($response->isNotModified($this->getRequest())) {
            return $response; // this will return the 304 if the cache is OK
        }*/

        //$media_formats = $this->get('sonata.media.pool')->getFormatNamesByContext('news');

        $news_list_big_highlight = array();
        $news_list_small_highlight = array();
        $i = 0;

        foreach($news_list as $news) {
            $news_list_big_highlight[$i]['title'] = $news[ 'title' ];
            $news_list_big_highlight[$i]['explode_title'] = explode( " ", $news[ 'title' ]);
            $news_list_big_highlight[$i]['slug'] = $news[ 'slug' ];
            $news_list_big_highlight[$i]['content'] = $news[ 'content' ];
            $news_list_big_highlight[$i]['u_username'] = $news[ 'u_username' ];

            $news_list_big_highlight[$i]['m_id'] = $news[ 'm_id' ];
            $news_list_big_highlight[$i]['m_width'] = 700;
            $news_list_big_highlight[$i]['m_height'] = $news[ 'm_height' ];
            $news_list_big_highlight[$i]['is_new'] = strtotime( $news[ 'updatedAt' ] ) > ( time() - 5 * 24 * 60 * 60 );

            $news_list_big_highlight[$i]['updatedAt'] = new \Datetime( $news[ 'updatedAt' ] );
            $i++;
        }

        
        return $this->render('BaseNewsBundle:Default:home_highlight.html.twig', array('news_list_big_highlight' => $news_list_big_highlight ),
                             $response );
    }

    /**
     * Generate the news feed
     *
     * @return Response XML Feed
     */
    public function feedAction() {
        $news_list = $this->getDoctrine()->getRepository('BaseNewsBundle:News')->findAll();

        $feed = $this->get('eko_feed.feed.manager')->get('article');
        $feed->addFromArray( $news_list );

        $feed->add(new \Base\NewsBundle\Entity\News());
        $feed->addItemField(new \Eko\FeedBundle\Field\MediaItemField('getFeedMediaItem'));

        return new Response($feed->render('rss')); // or 'atom'
    }
}

?>