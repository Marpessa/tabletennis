<?php
// src/Acme/MainBundle/Menu/MenuBuilder.php

namespace TableTennis\MainBundle\Footer;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class FooterBuilder
{
    private $factory;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createMainFooter(Request $request)
    {
        $footer = $this->factory->createItem('root');

        $footer->addChild('Navigate', array('route' => '_homepage', 'label' => 'Naviguer', 'attributes' => array('class' => 'footer_level_0 num0')));
        $footer['Navigate']->addChild('Home', array('route' => '_homepage', 'label' => 'Accueil'));
        $footer['Navigate']->addChild('Actualités', array('route' => '_baseNewsIndex', 'label' => 'Actualités'));
        $footer['Navigate']->addChild('Forum', array('route' => 'herzult_forum_index', 'label' => 'Forum'));
        $footer['Navigate']->addChild('Photos', array('route' => '_basePicsIndex', 'label' => 'Galerie Photos'));
        $footer['Navigate']->addChild('Videos', array('route' => '_baseVideosIndex', 'label' => 'Vidéos'));


        $footer->addChild('Informations', array('route' => '_homepage', 'label' => 'Informations', 'attributes' => array('class' => 'footer_level_0 num1')));
        $footer['Informations']->addChild('Rules', array('route' => '_baseCguIndex', 'label' => 'Règlement'));
        $footer['Informations']->addChild('Club', array('route' => '_basePageIndex',
                                                        'routeParameters' => array('slug' => 'presentation-du-club'),
                                                        'label' => 'Le Club'));
        $footer['Informations']->addChild('Partners', array('route' => '_tableTennisPartnerIndex', 'label' => 'Partenaires'));
        $footer['Informations']->addChild('Contact', array('route' => '_baseContactIndex', 'label' => 'Contact'));
        $footer['Informations']->addChild('DevBlog', array('route' => '_basePageIndex',
                                                           'routeParameters' => array('slug' => 'devblog'),
                                                           'label' => 'DevBlog'));
        $footer['Informations']->addChild('FindUs', array('route' => '_baseGoogleMapIndex', 'label' => 'Nous trouver'));
        $footer['Informations']->addChild('Sitemap', array('route' => '_baseSiteMapIndex', 'label' => 'Plan du site'));


        /*$footer->addChild('Credits', array('route' => '_homepage', 'label' => 'Crédits', 'attributes' => array('class' => 'footer_level_0 num2')));
        $footer['Credits']->addChild('Development', array('route' => '_homepage', 'label' => 'Développement par Manu'));
        $footer['Credits']->addChild('Content', array('route' => '_homepage', 'label' => 'Gestion du contenu par Steph'));
*/
        $footer->addChild('Who', array('route' => '_homepage', 'label' => 'Qui sommes nous ?', 'attributes' => array('class' => 'footer_level_0 num3')));
        $footer['Who']->addChild('Description', array('route' => '_basePageIndex',
                                                      'routeParameters' => array('slug' => 'presentation-du-club'),
                                                      'label' => 'CPFAIZENAY.com est avant toute chose votre site d\'actualités pongistes agésinates. CPFAIZENAY se propose de vous faire découvrir l\'actualité de notre club, ...'));


//        $footer->addChild('Club', array('route' => '_homepage', 'label' => 'Le Club'));
//        $footer['Club']->addChild('Presentation', array('route' => '_homepage', 'label' => 'Présentation'));
//        $footer['Club']->addChild('Licensees', array('route' => '_homepage', 'label' => 'Licenciés'));
//        $footer['Club']->addChild('Teams', array('route' => '_homepage', 'label' => 'Les équipes'));
//        $footer['Club']->addChild('ParticipationFeeClub', array('route' => '_homepage', 'label' => 'Participation du club au frais'));
//        $footer['Club']->addChild('Contact', array('route' => '_homepage', 'label' => 'Nous Contacter'));
//        $footer['Club']->addChild('Find', array('route' => '_homepage', 'label' => 'Nous Trouver'));
//
//        $footer->addChild('TableTennis', array('route' => '_homepage', 'label' => 'Tennis de table'));
        // ... add more children

        return $footer;
    }
}
?>