<?php
// src/Acme/MainBundle/Menu/MenuBuilder.php

namespace TableTennis\MainBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\SecurityContextInterface;

class MenuBuilder
{
    private $factory;
    private $context;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory, SecurityContextInterface $context)
    {
        $this->factory = $factory;
        $this->context = $context;
    }

    public function createMainMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');
        $menu->setCurrent($request->getRequestUri());
        $menu->setChildrenAttribute('class', 'nav navbar-nav');


        $menu->addChild('Home', array('route' => '_homepage', 'label' => 'Accueil'));
        //$menu['Home']->setCurrent(true);
        
        
        // Club
        $menu->addChild('club', array('uri' => '#le-club', 'label' => 'Le Club', 'attributes' => array('class' => 'dropdown')));
        $menu['club']->setChildrenAttribute('class', 'dropdown-menu');
        $menu['club']->setLinkAttribute('class', 'dropdown-toggle');
        $menu['club']->setLinkAttribute('data-toggle', 'dropdown');

        $menu['club']->addChild('sub-club', array('uri' => '#le-club', 'label' => 'Le Club', 'attributes' => array('class' => 'dropdown-submenu')));
        $menu['club']->addChild('workforce', array('uri' => '#effectifs', 'label' => 'Joueurs', 'attributes' => array('class' => 'dropdown-submenu')));
        $menu['club']->addChild('download', array('uri' => '#telechargement', 'label' => 'Téléchargement', 'attributes' => array('class' => 'dropdown-submenu')));

        $menu['club']['sub-club']->setChildrenAttribute('class', 'dropdown-menu');
        $menu['club']['workforce']->setChildrenAttribute('class', 'dropdown-menu');
        $menu['club']['download']->setChildrenAttribute('class', 'dropdown-menu');
        
        $menu['club']['sub-club']->addChild('presentation', array('route' => '_basePageIndex',
                                                                  'routeParameters' => array('slug' => 'presentation-du-club'),
                                                                  'label' => 'Présentation'));
        
        $menu['club']['sub-club']->addChild('schedules', array('route' => '_basePageIndex',
                                                                          'routeParameters' => array('slug' => 'les-horaires-d-ouverture'),
                                                                          'label' => 'Horaires'));
        
        $menu['club']['sub-club']->addChild('register', array('route' => '_basePageIndex',
                                                                          'routeParameters' => array('slug' => 'les-modalités-d-inscription'),
                                                                          'label' => 'Inscription'));
        
        $menu['club']['sub-club']->addChild('contact', array('route' => '_baseContactIndex', 'label' => 'Nous Contacter'));
        
        $menu['club']['sub-club']->addChild('find', array('route' => '_baseGoogleMapIndex', 'label' => 'Nous Trouver'));
        
        $menu['club']['workforce']->addChild('licensees', array('route' => '_tableTennisLicenseeIndex', 'label' => 'Licenciés'));
        $menu['club']['workforce']->addChild('licenseesEvo', array('route' => '_tableTennisLicenseeEvolution',
                                                                              'routeParameters' => array( 'current_date' => \date('Y-m') ),
                                                                              'label' => 'Progressions mensuelles'));
        $menu['club']['workforce']->addChild('licenseesRatingClub', array('route' => '_tableTennisLicenseeRatingClub',
                                                                                     'routeParameters' => array( 'current_date' => \date('Y-m') ),
                                                                                     'label' => 'Classement au sein du club'));
        //$menu['club']['workforce']->addChild('teams', array('route' => '_tableTennisTeamIndex', 'label' => 'Les équipes'));
        
        $menu['club']['download']->addChild('participationFeeClub', array('route' => '_basePageIndex',
                                                                          'routeParameters' => array('slug' => 'participation-du-club-au-frais'),
                                                                          'label' => 'Participation du club aux frais'));
        
        $menu['club']['download']->addChild('gameSheets', array('route' => '_basePageIndex',
                                                                'routeParameters' => array('slug' => 'feuilles-de-parties'),
                                                                'label' => 'Feuilles de parties'));
        
        // Tennis de table
        $menu->addChild('tableTennis', array('uri' => '#tennis-de-table', 'label' => 'Tennis de table', 'attributes' => array('class' => 'dropdown')));
        $menu['tableTennis']->setChildrenAttribute('class', 'dropdown-menu');
        $menu['tableTennis']->setLinkAttribute('class', 'dropdown-toggle');
        $menu['tableTennis']->setLinkAttribute('data-toggle', 'dropdown');

        $menu['tableTennis']->addChild('competitions', array('uri' => '#competitions', 'label' => 'Compétitions', 'attributes' => array('class' => 'dropdown-submenu')));
        $menu['tableTennis']->addChild('misc', array('uri' => '#divers', 'label' => 'Divers', 'attributes' => array('class' => 'dropdown-submenu')));

        $menu['tableTennis']['competitions']->setChildrenAttribute('class', 'dropdown-menu');
        $menu['tableTennis']['misc']->setChildrenAttribute('class', 'dropdown-menu');
        
        $menu['tableTennis']['competitions']->addChild('pointsCalculation', array('route' => '_tableTennisPointsCalculationIndex', 'label' => 'Calcul des points'));
        $menu['tableTennis']['competitions']->addChild('scoringTable', array('route' => '_tableTennisScoringTableIndex', 'label' => 'Barêmes des points'));
        $menu['tableTennis']['competitions']->addChild('coefficients', array('route' => '_tableTennisMatchTypeIndex', 'label' => 'Coëfficients des compétitions'));
        
        //$menu['tableTennis']['misc']->addChild('clubsList', array('route' => '_tableTennisClubIndex', 'label' => 'Liste des clubs'));
        $menu['tableTennis']['misc']->addChild('links', array('route' => '_basePageIndex',
                                                              'routeParameters' => array('slug' => 'liens'),
                                                              'label' => 'Liens utiles'));
        
        $menu->addChild('news', array('route' => '_baseNewsIndex', 'label' => 'Actualités'));
        //$menu->addChild('event', array('route' => '_baseEventIndex', 'label' => 'Evénements'));
        $menu->addChild('forum', array('route' => 'herzult_forum_index', 'label' => 'Forum'));
        $menu->addChild('photos', array('route' => '_basePicsIndex', 'label' => 'Photos'));
        $menu->addChild('videos', array('route' => '_baseVideosIndex', 'label' => 'Vidéos'));
        
        //$menu->addChild('Partners', array('route' => '_tableTennisPartnerIndex', 'label' => 'Partenaires'));

        $token = $this->context->getToken();
        if( !empty( $token ) ) {
            $user = $token->getUser();
        }
        if( !empty( $user ) && $this->context->isGranted("IS_AUTHENTICATED_REMEMBERED") && $this->context->isGranted('ROLE_ADMIN') ) {
            $menu->addChild('Admin', array('route' => 'sonata_admin_dashboard', 'label' => 'Admin', 'attributes' => array('class' => 'admin', 'target' => '_blank')));
        }
        // ... add more children

        return $menu;
    }
}
?>
