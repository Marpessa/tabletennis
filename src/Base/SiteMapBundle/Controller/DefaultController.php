<?php

namespace Base\SiteMapBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{

    public function indexAction()
    {
        $culture = 'fr';

        $urls = array();

        $urls['home'][] = array( 'title' => 'Accueil', 'loc' => $this->get('router')->generate('_homepage'), 'changefreq' => 'weekly', 'priority' => '1' );

        // News
        $urls['home'][] = array( 'title' => 'Actualités', 'loc' => $this->get('router')->generate('_baseNewsIndex', array( "culture" => $culture ) ),
                               'changefreq' => 'weekly', 'priority' => '1' );

        // Events
        $urls['home'][] = array( 'title' => 'Evénements', 'loc' => $this->get('router')->generate('_baseEventIndex', array( "culture" => $culture ) ),
                               'changefreq' => 'weekly', 'priority' => '1' );

        // Forum
        $urls['home'][] = array( 'title' => 'Forum', 'loc' => $this->get('router')->generate('herzult_forum_index', array( "culture" => $culture ) ),
                               'changefreq' => 'weekly', 'priority' => '0.8' );

        // Galerie photos
        $urls['home'][] = array( 'title' => 'Galerie photos', 'loc' => $this->get('router')->generate('_basePicsIndex', array( "culture" => $culture ) ),
                               'changefreq' => 'weekly', 'priority' => '0.8' );

        // Vidéos
        $urls['home'][] = array( 'title' => 'Vidéos', 'loc' => $this->get('router')->generate('_baseVideosIndex', array( "culture" => $culture ) ),
                               'changefreq' => 'weekly', 'priority' => '0.8' );

        // Calcul des points
        $urls['home'][] = array( 'title' => 'Calcul des points', 'loc' => $this->get('router')->generate('_tableTennisPointsCalculationIndex', array( "culture" => $culture ) ),
                               'changefreq' => 'monthly', 'priority' => '0.7' );

        // Barême des points
        $urls['home'][] = array( 'title' => 'Barême des points', 'loc' => $this->get('router')->generate('_tableTennisScoringTableIndex', array( "culture" => $culture ) ),
                               'changefreq' => 'monthly', 'priority' => '0.7' );

        // Coëfficients des compétitions
        $urls['home'][] = array( 'title' => 'Coëfficients des compétitions', 'loc' => $this->get('router')->generate('_tableTennisMatchTypeIndex', array( "culture" => $culture ) ),
                               'changefreq' => 'monthly', 'priority' => '0.7' );

        // Liste des clubs
        $urls['home'][] = array( 'title' => 'Liste des clubs', 'loc' => $this->get('router')->generate('_tableTennisClubIndex', array( "culture" => $culture ) ),
                               'changefreq' => 'monthly', 'priority' => '0.6' );

        // Contact
        $urls['home'][] = array( 'title' => 'Contact', 'loc' => $this->get('router')->generate('_baseContactIndex', array( "culture" => $culture ) ),
                               'changefreq' => 'monthly', 'priority' => '0.5' );

        // Nous trouver
        $urls['home'][] = array( 'title' => 'Nous trouver', 'loc' => $this->get('router')->generate('_baseGoogleMapIndex', array( "culture" => $culture ) ),
                               'changefreq' => 'monthly', 'priority' => '0.6' );

        // Licenciés
        $urls['home'][] = array( 'title' => 'Licenciés', 'loc' => $this->get('router')->generate('_tableTennisLicenseeIndex', array( "culture" => $culture ) ),
                               'changefreq' => 'monthly', 'priority' => '0.6' );

        // Equipes
        $urls['home'][] = array( 'title' => 'Equipes', 'loc' => $this->get('router')->generate('_tableTennisTeamIndex', array( "culture" => $culture ) ),
                               'changefreq' => 'monthly', 'priority' => '0.6' );

        // CGU
        $urls['home'][] = array( 'title' => 'CGU', 'loc' => $this->get('router')->generate('_baseCguIndex', array( "culture" => $culture ) ),
                               'changefreq' => 'weekly', 'priority' => '0.6' );

        // Pages
        $page_list = $this->getDoctrine()
                       ->getRepository('BasePageBundle:Page')
                       ->getPages()
                       ->getArrayResult();

        foreach( $page_list as $page ) {
            $urls['misc'][] = array( 'title' => $page['title'], 'loc' => $this->get('router')->generate('_basePageIndex', array( "slug" => $page['slug'],
                                                                                                      "culture" => $culture ) ),
                             'changefreq' => 'monthly', 'priority' => '0.8' );
        }


        /* Breadcrumbs */
        $breadcrumbs = $this->get( 'white_october_breadcrumbs' );
        $breadcrumbs->addItem( 'Accueil', $this->get("router")->generate( '_homepage' ));
        $breadcrumbs->addItem( 'Plan du site' );

        return $this->render('BaseSiteMapBundle:Default:index.html.twig', array( 'urls' => $urls ));
    }


    /**
     * @Template("BaseSiteMapBundle:Default:sitemap.xml.twig")
     */
    public function feedAction()
    {
        $hostname = $this->getRequest()->getHost();
        $urls = array();

        // add some urls homepage
        $urls[] = array('loc' => $this->get('router')->generate('_homepage'), 'lang' => 'fr');

        return array('urls' => $urls, 'hostname' => $hostname);
    }


    /**
     * @Template("BaseSiteMapBundle:Default:sitemap.xml.twig")
     */
    public function feedLangAction( $culture )
    {
        $hostname = $this->getRequest()->getHost();
        $urls = array();

        // add some urls homepage
        $urls[] = array( 'loc' => $this->get('router')->generate('_homepage'), 'changefreq' => 'weekly', 'priority' => '1' );

        if( !empty( $culture ) ) {
            
            // News
            $urls[] = array( 'loc' => $this->get('router')->generate('_baseNewsIndex', array( "culture" => $culture ) ),
                             'changefreq' => 'weekly', 'priority' => '1' );

            $news_list = $this->getDoctrine()
                              ->getRepository('BaseNewsBundle:News')
                              ->getNews()
                              ->getArrayResult();

            foreach( $news_list as $news ) {
                $urls[] = array( 'loc' => $this->get('router')->generate('_baseNewsShow', array( "slug" => $news['slug'],
                                                                                                 "year" => $news['updatedAt']->format('Y'),
                                                                                                 "month" => $news['updatedAt']->format('m'),
                                                                                                 "culture" => $culture ) ),
                                 'changefreq' => 'monthly', 'priority' => '0.8' );
            }

            // Events
            $urls[] = array( 'loc' => $this->get('router')->generate('_baseEventIndex', array( "culture" => $culture ) ),
                             'changefreq' => 'weekly', 'priority' => '1' );

            $event_list = $this->getDoctrine()
                           ->getRepository('BaseEventBundle:Event')
                           ->getEvents()
                           ->getArrayResult();

            foreach( $event_list as $event ) {
                $urls[] = array( 'loc' => $this->get('router')->generate('_baseEventShow', array( "slug" => $event[0]['slug'],
                                                                                                  "year" => $event[0]['updatedAt']->format('Y'),
                                                                                                  "month" => $event[0]['updatedAt']->format('m'),
                                                                                                  "culture" => $culture ) ),
                                 'changefreq' => 'monthly', 'priority' => '0.8' );
            }

            // Pages
            $page_list = $this->getDoctrine()
                           ->getRepository('BasePageBundle:Page')
                           ->getPages()
                           ->getArrayResult();

            foreach( $page_list as $page ) {
                $urls[] = array( 'loc' => $this->get('router')->generate('_basePageIndex', array( "slug" => $page['slug'],
                                                                                                  "culture" => $culture ) ),
                                 'changefreq' => 'monthly', 'priority' => '0.8' );
            }

            // Forum
            $urls[] = array( 'loc' => $this->get('router')->generate('herzult_forum_index', array( "culture" => $culture ) ),
                             'changefreq' => 'weekly', 'priority' => '0.8' );

            // Forum - Catégories
            $categories = $this->get('herzult_forum.repository.category')->findAll();

            foreach( $categories as $category ) {
                $urls[] = array( 'loc' => $this->get('router')->generate('herzult_forum_category_show', array( "slug" => $category->getSlug(), "culture" => $culture ) ),
                                 'changefreq' => 'weekly', 'priority' => '0.8' );
                $urls[] = array( 'loc' => $this->get('router')->generate('herzult_forum_category_topic_new', array( "slug" => $category->getSlug(), "culture" => $culture ) ),
                                 'changefreq' => 'monthly', 'priority' => '0.5' );
                $urls[] = array( 'loc' => $this->get('router')->generate('herzult_forum_category_topic_create', array( "slug" => $category->getSlug(), "culture" => $culture ) ),
                                 'changefreq' => 'monthly', 'priority' => '0.2' );


                // Forum - Topics
                $topics   = $this->get('herzult_forum.repository.topic')->findAll(true);

                foreach( $topics as $topic ) {
                    if( $topic->getCategory()->getId() == $category->getId() ) {
                        $urls[] = array( 'loc' => $this->get('router')->generate('herzult_forum_topic_show', array( 'timestamp' => $topic->getCreatedAt()->format('U'), "slug" => $topic->getSlug(), "categorySlug" => $category->getSlug(), "culture" => $culture ) ),
                                         'changefreq' => 'weekly', 'priority' => '0.8' );

                        $urls[] = array( 'loc' => $this->get('router')->generate('herzult_forum_topic_post_new', array( "slug" => $topic->getSlug(), "categorySlug" => $category->getSlug(), "culture" => $culture ) ),
                                         'changefreq' => 'monthly', 'priority' => '0.5' );

                        $urls[] = array( 'loc' => $this->get('router')->generate('herzult_forum_topic_post_create', array( "slug" => $topic->getSlug(), "categorySlug" => $category->getSlug(), "culture" => $culture ) ),
                                         'changefreq' => 'monthly', 'priority' => '0.2' );
                    }
                }

            }

            // Galerie photos
            $urls[] = array( 'loc' => $this->get('router')->generate('_basePicsIndex', array( "culture" => $culture ) ),
                             'changefreq' => 'weekly', 'priority' => '0.8' );

            // TODO Liste des photos

            // Vidéos
            $urls[] = array( 'loc' => $this->get('router')->generate('_baseVideosIndex', array( "culture" => $culture ) ),
                             'changefreq' => 'weekly', 'priority' => '0.8' );

            // TODO Liste des vidéos

            // Profil des licenciés
            $licensee_list = $this->getDoctrine()
                                  ->getRepository('TableTennisLicenseeBundle:Licensee')
                                  ->getLicensees()
                                  ->getArrayResult();

            $start_date = new \Datetime();
            $end_date = new \Datetime();

            foreach( $licensee_list as $licensee ) {
                $urls[] = array( 'loc' => $this->get('router')->generate('_sonataUserDashboard', array( "licensee_number" => $licensee['licensee_number'], "slug" => $licensee['slug'], "culture" => $culture ) ),
                                 'changefreq' => 'weekly', 'priority' => '0.8' );

                $urls[] = array( 'loc' => $this->get('router')->generate('_sonataUserMatchsList', array( "licensee_number" => $licensee['licensee_number'], "slug" => $licensee['slug'], "start_date" => $start_date->format('Y-m'), "end_date" => $end_date->format('Y-m'), "culture" => $culture ) ),
                                 'changefreq' => 'weekly', 'priority' => '0.8' );

                $urls[] = array( 'loc' => $this->get('router')->generate('_sonataUserEvolution', array( "licensee_number" => $licensee['licensee_number'], "slug" => $licensee['slug'], "start_date" => $start_date->format('Y-m'), "end_date" => $end_date->format('Y-m'), "culture" => $culture ) ),
                                 'changefreq' => 'weekly', 'priority' => '0.8' );

                $urls[] = array( 'loc' => $this->get('router')->generate('_sonataUserMatchsPie', array( "licensee_number" => $licensee['licensee_number'], "slug" => $licensee['slug'], "start_date" => $start_date->format('Y-m'), "end_date" => $end_date->format('Y-m'), "culture" => $culture ) ),
                                 'changefreq' => 'weekly', 'priority' => '0.8' );
            }

            // Calcul des points
            $urls[] = array( 'loc' => $this->get('router')->generate('_tableTennisPointsCalculationIndex', array( "culture" => $culture ) ),
                             'changefreq' => 'monthly', 'priority' => '0.7' );

            // Barême des points
            $urls[] = array( 'loc' => $this->get('router')->generate('_tableTennisScoringTableIndex', array( "culture" => $culture ) ),
                             'changefreq' => 'monthly', 'priority' => '0.7' );

            // Coëfficients des compétitions
            $urls[] = array( 'loc' => $this->get('router')->generate('_tableTennisMatchTypeIndex', array( "culture" => $culture ) ),
                             'changefreq' => 'monthly', 'priority' => '0.7' );

            // Liste des clubs
            $urls[] = array( 'loc' => $this->get('router')->generate('_tableTennisClubIndex', array( "culture" => $culture ) ),
                             'changefreq' => 'monthly', 'priority' => '0.6' );

            // Contact
            $urls[] = array( 'loc' => $this->get('router')->generate('_baseContactIndex', array( "culture" => $culture ) ),
                             'changefreq' => 'monthly', 'priority' => '0.5' );

            // Nous trouver
            $urls[] = array( 'loc' => $this->get('router')->generate('_baseGoogleMapIndex', array( "culture" => $culture ) ),
                             'changefreq' => 'monthly', 'priority' => '0.6' );

            // Licenciés
            $urls[] = array( 'loc' => $this->get('router')->generate('_tableTennisLicenseeIndex', array( "culture" => $culture ) ),
                             'changefreq' => 'monthly', 'priority' => '0.6' );

            // Equipes
            $urls[] = array( 'loc' => $this->get('router')->generate('_tableTennisTeamIndex', array( "culture" => $culture ) ),
                             'changefreq' => 'monthly', 'priority' => '0.6' );

            // CGU
            $urls[] = array( 'loc' => $this->get('router')->generate('_baseCguIndex', array( "culture" => $culture ) ),
                             'changefreq' => 'weekly', 'priority' => '0.6' );

            // Plan du site
            $urls[] = array( 'loc' => $this->get('router')->generate('_baseSiteMapIndex', array( "culture" => $culture ) ),
                             'changefreq' => 'weekly', 'priority' => '0.8' );

        }
        
        return array('urls' => $urls, 'hostname' => $hostname, 'lang' => $culture );
    }
}

?>