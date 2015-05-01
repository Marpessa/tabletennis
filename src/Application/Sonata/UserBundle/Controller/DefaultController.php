<?php

namespace Application\Sonata\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Application\Sonata\UserBundle\Form\AvatarForm;

class DefaultController extends Controller
{

    public function dashboardAction( $licensee_number, $slug = NULL, $user_email = NULL ){
        $start_date = new \Datetime();
        $start_date->sub( new \DateInterval('P5Y')); // si le joueur a arrêté depuis 5 ans, il y a un soucis...
        $end_date = new \Datetime();

        $licensee = NULL;
        $licenseeMatch_list = NULL;
        $licenseePoint_list = NULL;
        $graphData = array();
        $graphCategories = array();
        
        if( !empty( $licensee_number ) )
        {
            $licensee = $this->getLicensee( $licensee_number );
            
            if( empty( $user_email ) ) {
                $user = $this->getDoctrine()
                             ->getRepository('ApplicationSonataUserBundle:User')
                             ->findOneBy( array( 'licensee_number' => $licensee_number ) );
            }else{
                $user = $this->getDoctrine()
                             ->getRepository('ApplicationSonataUserBundle:User')
                             ->findOneBy( array( 'email' => $user_email ) );
            }

            $licenseeMatch_list = $this->getDoctrine()
                                   ->getRepository('TableTennisLicenseeBundle:LicenseeMatch')
                                   ->getLicenseeMatchs( $licensee[ "id" ], $start_date, $end_date, 5 )
                                   ->getArrayResult();

            $licenseePoint_list = $this->getDoctrine()
                                       ->getRepository('TableTennisLicenseeBundle:LicenseePoint')
                                       ->getLicenseePoints( $licensee[ "id" ] )
                                       ->getArrayResult();

            $licenseePoint_list = array_reverse( $licenseePoint_list );

            /* Graph Data */
            $graphData = array();
            $graphCategories = array();

            foreach($licenseePoint_list as $licenseePoint){

                if(!empty($licenseePoint[ 'nb_points_fftt' ])){
                    $graphData[] = intval($licenseePoint[ 'nb_points_fftt' ]);
                }else{
                    $graphData[] = end($graphData);
                }

                $datetime_points = new \DateTime();
                $month = date('m', ($licenseePoint[ 'datetime_points' ]->getTimestamp())) - 1;
                $year = date('Y', ($licenseePoint[ 'datetime_points' ]->getTimestamp()));
                if($month < 1){
                    $month = 12;
                    $year--;
                }
                $datetime_points->setDate($year, $month, '01');
                $datetime_points->setTime(0, 0, 0);

                $graphCategories[] = (string) date("M", $datetime_points->getTimestamp()) . ".";
            }
        }

        $mensualPoint = "-";
        $evoMensualPoint = "-";
        $startSeasonMensualPoint = "-";

        $mensualPoint = $licensee[ "nb_current_points" ];
        $evoMensualPoint = $licensee[ "monthly_increase" ];
        $startSeasonMensualPoint = isset( $graphData[0] ) ? $graphData[0] : NULL;

        $pieData = array();
        if( !empty( $licenseeMatch_list ) ) {
            $pieData = $this->getPieData( $licenseeMatch_list );
        }

        /* Breadcrumbs */
        $breadcrumbs = $this->get( 'white_october_breadcrumbs' );
        $breadcrumbs->addItem( 'Accueil', $this->get("router")->generate( '_homepage' ));
        $breadcrumbs->addItem( 'Profil de ' . $licensee[ "lastname" ] . " " . $licensee[ "firstname" ] );

        $media_formats = $this->get('sonata.media.pool')->getFormatNamesByContext('avatar');

        return $this->render('ApplicationSonataUserBundle:Default:dashboard.html.twig', array( "licensee" => $licensee,
                                                                                               "user" => $user,
                                                                                               "licenseeMatch_list" => $licenseeMatch_list,
                                                                                               "graphData" => $graphData,
                                                                                               "graphCategories" => $graphCategories,
                                                                                               "mensualPoint" => $mensualPoint,
                                                                                               "startSeasonMensualPoint" => $startSeasonMensualPoint,
                                                                                               "evoMensualPoint" => $evoMensualPoint,
                                                                                               "firstMatch" => !empty( $licenseeMatch_list ) ? reset( $licenseeMatch_list ) : NULL,
                                                                                               "lastMatch" => !empty( $licenseeMatch_list ) ? end( $licenseeMatch_list ) : NULL,
                                                                                               "pieData" => $pieData,
                                                                                               'media_formats' => $media_formats
                                                                                             ));
    }

    public function matchsListAction( $licensee_number, $start_date, $end_date ){
        $start_date = new \DateTime( $start_date );
        $end_date = new \DateTime( $end_date );

        $monthsNav = $this->getMonthsNav( $start_date );

        $licensee = $this->getLicensee( $licensee_number );

        $licenseeMatch_list = $this->getDoctrine()
                                   ->getRepository('TableTennisLicenseeBundle:LicenseeMatch')
                                   ->getLicenseeMatchs( $licensee[ "id" ], $start_date, $end_date, 100 )
                                   ->getArrayResult();

        $mensualPoint = "-";
        $mensualPoint = $licensee[ "nb_current_points" ];

        /* Breadcrumbs */
        $breadcrumbs = $this->get( 'white_october_breadcrumbs' );
        $breadcrumbs->addItem( 'Accueil', $this->get("router")->generate( '_homepage' ));
        $breadcrumbs->addItem( 'Profil de ' . $licensee[ "lastname" ] . " " . $licensee[ "firstname" ], $this->get("router")->generate( '_sonataUserDashboard', array( 'licensee_number' => $licensee_number ,'slug' => $licensee['slug'] ) ) );
        $breadcrumbs->addItem( 'Matchs joués' );

        return $this->render('ApplicationSonataUserBundle:Default:matchsList.html.twig', array( "licensee" => $licensee,
                                                                                                "licenseeMatch_list" => $licenseeMatch_list,
                                                                                                "mensualPoint" => $mensualPoint,
                                                                                                "startDate" => $start_date->format('Y-m'),
                                                                                                "endDate" => $end_date->format('Y-m'),
                                                                                                "monthsNav" => $monthsNav
                                                                                             ));
    }

    public function evolutionAction( $licensee_number, $start_date, $end_date ){
        $start_date = new \DateTime( $start_date );
        $end_date = new \DateTime( $end_date );

        $monthsNav = $this->getMonthsNav( $start_date );

        $licensee = $this->getLicensee( $licensee_number );

        $licenseePoint_list = $this->getDoctrine()
                                   ->getRepository('TableTennisLicenseeBundle:LicenseePoint')
                                   ->getLicenseePoints( $licensee[ "id" ] )
                                   ->getArrayResult();

        $licenseePoint_list = array_reverse( $licenseePoint_list );

        /* Graph Data */
        $graphData = array();
        $graphCategories = array();

        foreach($licenseePoint_list as $licenseePoint){

            if(!empty($licenseePoint[ 'nb_points_fftt' ])){
                $graphData[] = intval($licenseePoint[ 'nb_points_fftt' ]);
            }else{
                $graphData[] = end($graphData);
            }

            $datetime_points = new \DateTime();
            $month = date('m', ($licenseePoint[ 'datetime_points' ]->getTimestamp())) - 1;
            $year = date('Y', ($licenseePoint[ 'datetime_points' ]->getTimestamp()));
            if($month < 1){
                $month = 12;
                $year--;
            }
            $datetime_points->setDate($year, $month, '01');
            $datetime_points->setTime(0, 0, 0);

            $graphCategories[] = (string) date("M", $datetime_points->getTimestamp()) . ".";
        }

        $mensualPoint = "-";
        $evoMensualPoint = "-";
        $startSeasonMensualPoint = "-";

        $mensualPoint = $licensee[ "nb_current_points" ];
        $evoMensualPoint = $licensee[ "monthly_increase" ];
        $startSeasonMensualPoint = isset( $graphData[0] ) ? $graphData[0] : NULL;

        /* Breadcrumbs */
        $breadcrumbs = $this->get( 'white_october_breadcrumbs' );
        $breadcrumbs->addItem( 'Accueil', $this->get("router")->generate( '_homepage' ));
        $breadcrumbs->addItem( 'Profil de ' . $licensee[ "lastname" ] . " " . $licensee[ "firstname" ], $this->get("router")->generate( '_sonataUserDashboard', array( 'licensee_number' => $licensee_number ,'slug' => $licensee['slug'] ) ) );
        $breadcrumbs->addItem( 'Evolution' );

        return $this->render('ApplicationSonataUserBundle:Default:evolution.html.twig', array( "licensee" => $licensee,
                                                                                               "graphData" => $graphData,
                                                                                               "graphCategories" => $graphCategories,
                                                                                               "mensualPoint" => $mensualPoint,
                                                                                               "startDate" => $start_date->format('Y-m'),
                                                                                               "endDate" => $end_date->format('Y-m'),
                                                                                               "monthsNav" => $monthsNav
                                                                                             ));
    }

    public function perfConsAction( $licensee_number, $start_date, $end_date ){
        $start_date = new \DateTime( $start_date );
        $end_date = new \DateTime( $end_date );

        $monthsNav = $this->getMonthsNav( $start_date );

        $licensee = $this->getLicensee( $licensee_number );

        $licenseeMatch_list = $this->getDoctrine()
                                   ->getRepository('TableTennisLicenseeBundle:LicenseeMatch')
                                   ->getLicenseeMatchs( $licensee[ "id" ], $start_date, $end_date, 100 )
                                   ->getArrayResult();

        $pieData = $this->getPieData( $licenseeMatch_list );

        /* Breadcrumbs */
        $breadcrumbs = $this->get( 'white_october_breadcrumbs' );
        $breadcrumbs->addItem( 'Accueil', $this->get("router")->generate( '_homepage' ));
        $breadcrumbs->addItem( 'Profil de ' . $licensee[ "lastname" ] . " " . $licensee[ "firstname" ], $this->get("router")->generate( '_sonataUserDashboard', array( 'licensee_number' => $licensee_number ,'slug' => $licensee['slug'] ) ) );
        $breadcrumbs->addItem( 'Perfs / Contres' );

        return $this->render('ApplicationSonataUserBundle:Default:perfCons.html.twig', array( "licensee" => $licensee,
                                                                                              "pieData" => $pieData,
                                                                                              "startDate" => $start_date->format('Y-m'),
                                                                                              "endDate" => $end_date->format('Y-m'),
                                                                                              "monthsNav" => $monthsNav
                                                                                             ));
    }

    public function profileDashboardAction(){
        $user = $this->container->get('security.context')->getToken()->getUser();

        if (!is_object($user) || !$user instanceof \Application\Sonata\UserBundle\Entity\User ) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $licensee_number = $user->getLicenseeNumber();
        $user_email = $user->getEmail();

        if( empty( $licensee_number ) ) {
            return new RedirectResponse($this->container->get('router')->generate('_sonataUserEditProfileGeneralInfos'));
        }

        return $this->dashboardAction( $licensee_number, NULL, $user_email );
    }

    public function profileMatchsListAction(){
        $user = $this->container->get('security.context')->getToken()->getUser();

        if (!is_object($user) || !$user instanceof \Application\Sonata\UserBundle\Entity\User ) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $licensee_number = $user->getLicenseeNumber();

        if( empty( $licensee_number ) ) {
            return new RedirectResponse($this->container->get('router')->generate('_sonataUserEditProfileGeneralInfos'));
        }

        $start_date = new \DateTime();
        $end_date = new \DateTime();

        return $this->matchsListAction( $licensee_number, $start_date->format('Y-m'), $end_date->format('Y-m') );
    }

    public function profileEvolutionAction(){
        $user = $this->container->get('security.context')->getToken()->getUser();

        if (!is_object($user) || !$user instanceof \Application\Sonata\UserBundle\Entity\User ) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $licensee_number = $user->getLicenseeNumber();

        if( empty( $licensee_number ) ) {
            return new RedirectResponse($this->container->get('router')->generate('_sonataUserEditProfileGeneralInfos'));
        }

        $start_date = new \DateTime();
        $end_date = new \DateTime();

        return $this->evolutionAction( $licensee_number, $start_date->format('Y-m'), $end_date->format('Y-m') );
    }

    public function profilePerfConsAction(){
        $user = $this->container->get('security.context')->getToken()->getUser();

        if (!is_object($user) || !$user instanceof \Application\Sonata\UserBundle\Entity\User ) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $licensee_number = $user->getLicenseeNumber();

        if( empty( $licensee_number ) ) {
            return new RedirectResponse($this->container->get('router')->generate('_sonataUserEditProfileGeneralInfos'));
        }

        $start_date = new \DateTime();
        $end_date = new \DateTime();

        return $this->perfConsAction( $licensee_number, $start_date->format('Y-m'), $end_date->format('Y-m') );
    }

    /**
     * Edit the avatar user
     */
    public function editProfileAvatarAction(Request $request)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof \Application\Sonata\UserBundle\Entity\User ) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $form = $this->createForm( new AvatarForm() );

        $request = $this->getRequest();

        if ($request->isMethod('POST')) {
            $form->submit($request->request->get($form->getName()));

            if ($form->isValid()) {
                $media = new \Application\Sonata\MediaBundle\Entity\Media;
                $media->setProviderName('sonata.media.provider.image');
                $media->setContext('avatar');

                $file = $request->files->get('application_sonata_user_avatar');

                $media->setBinaryContent( $file["media_id"]["binaryContent"] );
                $media->setName( $user->getUsername() );
                $media->setEnabled( TRUE );

                $mediaManager = $this->container->get('sonata.media.manager.media');
                $mediaManager->save($media);

                $user->setMediaId($media);

                $em = $this->getDoctrine()->getManager();
                $em->flush();
                $em->refresh($user);

                $request->getSession()->getFlashBag()->add('flash-notice', 'Votre avatar a bien été modifié.');

                return $this->redirect( $this->generateUrl('_sonataUserEditProfileAvatar') );
            }
        }

        /* Breadcrumbs */
        $breadcrumbs = $this->container->get( 'white_october_breadcrumbs' );
        $breadcrumbs->addItem( 'Accueil', $this->container->get("router")->generate( '_homepage' ));
        $breadcrumbs->addItem( 'Profil de ' . $user->getLastname() . ' ' . $user->getFirstname(), $this->container->get("router")->generate( '_sonataUserProfileDashboard' ));
        $breadcrumbs->addItem( 'Modifier mon avatar' );

        $media_formats = $this->get('sonata.media.pool')->getFormatNamesByContext('avatar');

        return $this->render('ApplicationSonataUserBundle:Default:edit_avatar.html.twig', array( 'form' => $form->createView(),
                                                                                                 'user' => $user,
                                                                                                 'media_formats' => $media_formats
                                                                                               ));
    }


    /**
     * Edit tennis table user
     */
    /*public function editProfileTableTennisInfosAction(Request $request)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof \Application\Sonata\UserBundle\Entity\User ) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $user = $this->container->get('security.context')->getToken()->getUser();

        try {
            $licensee = $this->getDoctrine()
                                 ->getRepository('TableTennisLicenseeBundle:Licensee')
                                 ->getCurrentLicenseeUser( $user->getLicenseeId() )
                                 ->getSingleResult();
        } catch (\Doctrine\Orm\NoResultException $e) {}

        $form = $this->createForm( new ProfileTennisTableForm() );

        $request = $this->getRequest();

        if ($request->isMethod('POST')) {
            
            $form->bindRequest($request);

            if ($form->isValid()) {

                $data = $form->getData();

                $licensee->setLicenseeNumber( $data['licensee_number'] );
                $licensee->setCategory( $data['category'] );

                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($licensee);
                $em->flush();

                return $this->redirect( $this->generateUrl('_sonataUserEditProfileTableTennisInfos') );
            }
        }

        // Default Values
        if( !empty( $licensee ) ) {
            $form->get('licensee_number')->setData( $licensee->getLicenseeNumber() );
            $form->get('category')->setData( $licensee->getCategory() );
        } else {
            $licensee = new Licensee();
        }
        
        // Breadcrumbs
        $breadcrumbs = $this->container->get( 'white_october_breadcrumbs' );
        $breadcrumbs->addItem( 'Accueil', $this->container->get("router")->generate( '_homepage' ));
        $breadcrumbs->addItem( 'Profil de ' . $user->getLastname() . ' ' . $user->getFirstname(), $this->container->get("router")->generate( '_sonataUserProfileDashboard' ));
        $breadcrumbs->addItem( 'Modifier mes informations de tennis de table' );

        return $this->render('ApplicationSonataUserBundle:Default:edit_tabletennis_infos.html.twig', array( 'form' => $form->createView(),
                                                                                                            'licensee', $licensee ));
    }*/
    

    private function getPieData( $licenseeMatch_list ){
        $pieData = array();

        $pieData[ "perfData" ] = array();
        $pieData[ "perfDataPerc" ] = array();
        $pieData[ "nbPerfData" ] = 0;
        $pieData[ "nbPerfDataPerc" ] = 0;

        $pieData[ "consData" ] = array();
        $pieData[ "consDataPerc" ] = array();
        $pieData[ "nbConsData" ] = 0;
        $pieData[ "nbConsDataPerc" ] = 0;

        foreach( $licenseeMatch_list as $licensee_match ) {
            if( intval($licensee_match[ "points_evaluation" ]) >= 0 ) {
                $pieData[ "nbPerfData" ]++;
                if( !empty( $pieData[ "perfData" ][$licensee_match[ "opponent_ranking" ]] ) ){
                    $pieData[ "perfData" ][$licensee_match[ "opponent_ranking" ]]++;
                }else{
                    $pieData[ "perfData" ][$licensee_match[ "opponent_ranking" ]] = 1;
                }
            }else{
                $pieData[ "nbConsData" ]++;
                if( !empty( $pieData[ "consData" ][$licensee_match[ "opponent_ranking" ]] ) ){
                    $pieData[ "consData" ][$licensee_match[ "opponent_ranking" ]]++;
                }else{
                    $pieData[ "consData" ][$licensee_match[ "opponent_ranking" ]] = 1;
                }
            }
        }

        ksort( $pieData[ "perfData" ] );
        ksort( $pieData[ "consData" ] );

        $nbTotalMatchs = $pieData[ "nbPerfData" ] + $pieData[ "nbConsData" ];

        foreach( $pieData[ "perfData" ] as $perf ) {
            $perc = (int) number_format( $perf / $nbTotalMatchs * 100, 2 );
            $pieData[ "perfDataPerc" ][] = $perc;
            $pieData[ "nbPerfDataPerc" ] += $perc;
        }

        foreach( $pieData[ "consData" ] as $cons ) {
            $perc = (int) number_format( $cons / $nbTotalMatchs * 100, 2 );
            $pieData[ "consDataPerc" ][] = $perc;
            $pieData[ "nbConsDataPerc" ] += $perc;
        }

        $pieData[ "perfData" ] = array_keys( $pieData[ "perfData" ] );
        $pieData[ "consData" ] = array_keys( $pieData[ "consData" ] );

        return $pieData;
    }

    private function getMonthsNav( $currentDate ) {

        $monthsNav = array();
        
        $monthsFr = array( 1 => "Jan.", 2 => "Fev.", 3 => "Mar.", 4 => "Avr.", 5 => "Mai", 6 => "Juin",
                           7 => "Jui.", 8 => "Aou.", 9 => "Sep.", 10 => "Oct.", 11 => "Nov.", 12 => "Dec." );
        
        for($monthNum = 6; $monthNum>0; $monthNum--) {
            $currentDateTmp = clone( $currentDate );
            $date = $currentDateTmp->sub( new \DateInterval('P' . $monthNum. 'M'));
            $monthsNav[$date->format('Y-m')] = $monthsFr[ (int) $date->format('m') ] . ' ' . $date->format('y');
        }

        $currentDateTmp = clone( $currentDate );
        $monthsNav[$currentDateTmp->format('Y-m')] = $monthsFr[ (int) $currentDateTmp->format('m') ] . ' ' . $currentDateTmp->format('y');

        for($monthNum = 1; $monthNum<7; $monthNum++) {
            $currentDateTmp = clone( $currentDate );
            $date = $currentDateTmp->add( new \DateInterval('P' . $monthNum. 'M'));
            $monthsNav[$date->format('Y-m')] = $monthsFr[ (int) $date->format('m') ] . ' ' . $date->format('y');
        }

        return $monthsNav;
    }

    private function getLicensee( $licensee_number ) {
        $licensee = $this->getDoctrine()
                         ->getRepository('TableTennisLicenseeBundle:Licensee')
                         ->getCurrentLicensee( $licensee_number )
                         ->getArrayResult();

        $licensee = array_shift( $licensee );

        return $licensee;
    }
}