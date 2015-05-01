<?php

namespace TableTennis\LicenseeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
	    $licensee_list = $this->getDoctrine()
                              ->getRepository('TableTennisLicenseeBundle:Licensee')
                              ->getLicensees()
                              ->getArrayResult();

        /* Breadcrumbs */
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Accueil", $this->get("router")->generate("_homepage"));
        $breadcrumbs->addItem("Liste des licenciés du club");

        return $this->render('TableTennisLicenseeBundle:Default:index.html.twig', array('licensee_list' => $licensee_list));
    }

    public function homeEvolutionAction()
    {
        $licensee_point_list = $this->getDoctrine()
                                    ->getRepository('TableTennisLicenseeBundle:Licensee')
                                    ->getLicenseesByMonthlyIncrease()
                                    ->getArrayResult();

        $firstLicenseePoint = reset($licensee_point_list);
        
        $months = array('01' => 'Janvier', '02' => 'Février', '03' => 'Mars', '04' => 'Avril', '05' => 'Mai', '06' => 'Juin',
                        '07' => 'Juillet', '08' => 'Août', '09' => 'Septembre', '10' => 'Octobre', '11' => 'Novembre', '12' => 'Décembre');
        
        $currentDate = new \Datetime( "now" );
        $lastMonthPoint = $months[$currentDate->format('m')];
        
        // Cache
        $response = new Response();
        $response->setEtag( md5( $firstLicenseePoint['monthly_increase'] . $firstLicenseePoint['slug'] . $lastMonthPoint ) );
        $response->setMaxAge(24*60*60);
        $response->setSharedMaxAge(24*60*60);
        $response->setPublic();

        if ($response->isNotModified($this->getRequest())) {
            return $response; // this will return the 304 if the cache is OK
        }
        

        $evo[ "best" ] = array();
        $evo[ "worst" ] = array();

        $i = 0;
        while( $i < 5 ) {

            $licensee_point = array_shift( $licensee_point_list );

            $evo[ "best" ][] = array("name" => ($licensee_point['lastname'] . " " . $licensee_point['firstname']),
                                     "slug" => $licensee_point['slug'],
                                     "licensee_number" => $licensee_point['licensee_number'],
                                     "points" =>  intval( $licensee_point['monthly_increase'] ) );

            $i++;
        }

        

        $i = 0;
        while( $i < 5 ) {

            $licensee_point = array_pop( $licensee_point_list );

            $evo[ "worst" ][] = array("name" => ($licensee_point['lastname'] . " " . $licensee_point['firstname']),
                                      "slug" => $licensee_point['slug'],
                                      "licensee_number" => $licensee_point['licensee_number'],
                                      "points" =>  intval( $licensee_point['monthly_increase'] ) );

            $i++;
        }



        /*$evo[ "best" ] = array();
        $evo[ "worst" ] = array();
        
        //$datetimePointsFirstMonth = null;
        //$licensee_id = null;
        
        $lastMonthPoint = reset($licensee_point_list);
        $months = array('01' => 'Janvier', '02' => 'Février', '03' => 'Mars', '04' => 'Avril', '05' => 'Mai', '06' => 'Juin',
                        '07' => 'Juillet', '08' => 'Août', '09' => 'Septembre', '10' => 'Octobre', '11' => 'Novembre', '12' => 'Décembre');
        $lastMonthPoint = $months[$lastMonthPoint[ "lp_datetime_points" ]->format('m')];

        $var_nb_points = 0;
        $nbPointsFirstMonth = NULL;
        $licId = NULL;
        foreach( $licensee_point_list as $licensee_point ) {

            // Si ce n'est pas le même licencié entre les points du 1er mois et du 2ème mois on recommence
            if( !empty( $nbPointsFirstMonth ) && $licId != $licensee_point['l_id'] ) { 
                $nbPointsFirstMonth = NULL;
            }

            if( empty( $nbPointsFirstMonth ) ) {
                $nbPointsFirstMonth = $licensee_point['lp_nb_points_fftt']; // Récupération du 1er mois
                $licId = $licensee_point['l_id'];
            }else{
                $var_nb_points = $licensee_point['lp_nb_points_fftt'] - $nbPointsFirstMonth; // Récupération du 1er mois et 2ème mois (soustraction)
                
                if($var_nb_points > 0) { // Best
                    $evo[ "best" ][] = array("name" => ($licensee_point['l_lastname'] . " " . $licensee_point['l_firstname']),
                                             "slug" => $licensee_point['l_slug'],
                                             "licensee_number" => $licensee_point['l_licensee_number'],
                                             "points" =>  intval( $var_nb_points ) );
                } else { // Worst
                    $evo[ "worst" ][] = array("name" => ($licensee_point['l_lastname'] . " " . $licensee_point['l_firstname']),
                                              "licensee_number" => $licensee_point['l_licensee_number'],
                                              "slug" => $licensee_point['l_slug'],
                                              "points" => intval( $var_nb_points ) );
                }
                $nbPointsFirstMonth = NULL;
                $licId = NULL;
                $var_nb_points = 0;
            }
        }
        usort($evo[ "best" ], function($a, $b){ return $a["points"] < $b["points"]; });
        usort($evo[ "worst" ], function($a, $b){ return $a["points"] > $b["points"]; });

        while( count( $evo[ "best" ] ) > 6 ) {
            array_pop( $evo[ "best" ] );
        }

        while( count( $evo[ "worst" ] ) > 6 ) {
            array_pop( $evo[ "worst" ] );
        }*/

        return $this->render('TableTennisLicenseeBundle:Default:homeEvolution.html.twig', array( 'lastMonthPoint' => $lastMonthPoint, 
                                                                                                 'evo_best_list' => $evo[ "best" ],
                                                                                                 'evo_worst_list' => $evo[ "worst" ] ),
                             $response );
    }
    
    public function evolutionAction( $current_date )
    {
        $current_date = new \DateTime( $current_date );
        
        $previous_date = clone( $current_date );
        $previous_date->sub( new \DateInterval('P1M') );
        
        $licensee_point_list = $this->getDoctrine()
                                    ->getRepository('TableTennisLicenseeBundle:Licensee')
                                    ->getLicenseesByMonthlyIncrease()
                                    ->getArrayResult();

        $months = array('01' => 'Janvier', '02' => 'Février', '03' => 'Mars', '04' => 'Avril', '05' => 'Mai', '06' => 'Juin',
                        '07' => 'Juillet', '08' => 'Août', '09' => 'Septembre', '10' => 'Octobre', '11' => 'Novembre', '12' => 'Décembre');
        
        $lastMonthPoint = $months[$previous_date->format('m')];
        
        return $this->render('TableTennisLicenseeBundle:Default:evolution.html.twig', array( "current_date" => $current_date,
                                                                                             "previous_date" => $previous_date,
                                                                                             "lastMonthPoint" => $lastMonthPoint,
                                                                                             "licensee_point_list" => $licensee_point_list
                                                                                           ));
    }
    
    public function ratingClubAction( $current_date )
    {
        $current_date = new \DateTime( $current_date );
        
        $previous_date = clone( $current_date );
        $previous_date->sub( new \DateInterval('P1M') );
        
        $licensee_point_list = $this->getDoctrine()
                                    ->getRepository('TableTennisLicenseeBundle:Licensee')
                                    ->getLicenseesByNbCurrentPoints()
                                    ->getArrayResult();

        $months = array('01' => 'Janvier', '02' => 'Février', '03' => 'Mars', '04' => 'Avril', '05' => 'Mai', '06' => 'Juin',
                        '07' => 'Juillet', '08' => 'Août', '09' => 'Septembre', '10' => 'Octobre', '11' => 'Novembre', '12' => 'Décembre');
        
        $lastMonthPoint = $months[$current_date->format('m')];
        
        foreach( $licensee_point_list as $licensee_point ) {
            $i=1;

            $old_points_current_licensee = $licensee_point[ "nb_current_points"] - $licensee_point[ "monthly_increase" ];
            
            foreach( $licensee_point_list as $licensee_point_tmp ) {
                $old_points_licensee = $licensee_point_tmp[ "nb_current_points"] - $licensee_point_tmp[ "monthly_increase" ];
                if( $old_points_licensee > $old_points_current_licensee ) {
                    $i++;
                }
                
                $old_indexes[$licensee_point["licensee_number"]] = $i;
            }
        }
        
        return $this->render('TableTennisLicenseeBundle:Default:ratingClub.html.twig', array( "current_date" => $current_date,
                                                                                              "previous_date" => $previous_date,
                                                                                              "lastMonthPoint" => $lastMonthPoint,
                                                                                              "licensee_point_list" => $licensee_point_list,
                                                                                              "old_indexes" => $old_indexes
                                                                                            ));
    }
    
    
}