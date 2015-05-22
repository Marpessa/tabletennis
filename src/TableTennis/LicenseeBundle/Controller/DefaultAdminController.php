<?php

namespace TableTennis\LicenseeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TableTennis\LicenseeBundle\Entity\Licensee;
use TableTennis\LicenseeBundle\Entity\LicenseePoint;
use TableTennis\LicenseeBundle\Entity\LicenseeMatch;

class DefaultAdminController extends Controller
{
    private static $licensees_list_parsing_format = array(
        1 => "index",
        0 => "licensee_number",
        2 => "lastname",
        3 => "firstname",
        4 => "ranking",
        5 => "nb_mensual_points",
        6 => "category",
        7 => "title",
    );

    public function synchronizationAction()
    {
        $licensee_list = $this->getDoctrine()
                              ->getRepository('TableTennisLicenseeBundle:Licensee')
                              ->getLicensees()
                              ->getArrayResult();

        $this->cler_request_fftt = NULL; // token FFTT permettant de récupérer les matchs
        $this->reqidMen_request_fftt = NULL; // token FFTT permettant de récupérer les matchs masculins (ne sert plus)
        $this->reqidWomen_request_fftt = NULL; // token FFTT permettant de récupérer les matchs féminins (ne sert plus)

        // Mise à jour des licenciés et de leurs points
        $licensees_list_parsing = array();
        $licensees_list_parsing = $this->parseLicenseesFFTTCom( $licensees_list_parsing, 0, 0, $this->container->getParameter('licensees_fftt.reqid.men') );
        $licensees_list_parsing = $this->parseLicenseesFFTTCom( $licensees_list_parsing, 0, count($licensees_list_parsing), $this->container->getParameter('licensees_fftt.reqid.women') );

        $this->updateLicensees( $licensees_list_parsing );
        
        $licensee_list = $this->getDoctrine()
                              ->getRepository('TableTennisLicenseeBundle:Licensee')
                              ->getLicensees()
                              ->getArrayResult();
        
        foreach( $licensee_list as $key => $licensee ) {
            $licenseePoint = $this->getDoctrine()->getRepository('TableTennisLicenseeBundle:LicenseePoint')
                                                 ->getLicenseePoints( $licensee["id"], 1 )
                                                 ->getSingleResult();
            
            // sauvegarder dernière date du cron et vérifier que le mois correspond si oui supprimer du tablau des licenciés pour éviter trop d'appels
            //echo "<pre>"; var_dump( $licenseePoint ); echo "</pre>";
        }
        
        die();
        
        /*
        // Mise à jour de matchs des licenciés
        $licensees_match_list_parsing = array();
        $licensees_match_list_parsing = $this->parseLicenseesMatchsFFTTCom( $licensee_list );*/
        
        //$this->updateLicenseesMatchs( $licensees_match_list_parsing );

        return $this->render('TableTennisLicenseeBundle:DefaultAdmin:synchronization.html.twig', array(
            'base_template'   => $this->container->get('sonata.admin.pool')->getTemplate('layout'),
            'admin_pool'      => $this->container->get('sonata.admin.pool'),
            'blocks'          => $this->container->getParameter('sonata.admin.configuration.dashboard_blocks'),
            'licensee_list'  => $licensee_list
        ));
    }

    public function doSynchronizationAction()
    {
        $request = $this->container->get('request');

        if($request->isXmlHttpRequest())
        {
            $this->cler_request_fftt = NULL; // token FFTT permettant de récupérer les matchs
            $this->reqidMen_request_fftt = NULL; // token FFTT permettant de récupérer les matchs masculins
            $this->reqidWomen_request_fftt = NULL; // token FFTT permettant de récupérer les matchs féminins
            
            // Mise à jour des licenciés et de leurs points
            $licensees_list_parsing = array();
            $licensees_list_parsing = $this->parseLicenseesFFTTCom( $licensees_list_parsing, 0, 0, $this->container->getParameter('licensees_fftt.reqid.men') );
            $licensees_list_parsing = $this->parseLicenseesFFTTCom( $licensees_list_parsing, 0, count($licensees_list_parsing), $this->container->getParameter('licensees_fftt.reqid.women') );

            $this->updateLicensees( $licensees_list_parsing );
            
            $licensee_list = $this->getDoctrine()
                              ->getRepository('TableTennisLicenseeBundle:Licensee')
                              ->getLicensees()
                              ->getArrayResult();

            // Mise à jour des matchs des licenciés
            $licensees_match_list_parsing = array();
            $licensees_match_list_parsing = $this->parseLicenseesMatchsFFTTCom( $licensee_list );

            $this->updateLicenseesMatchs( $licensees_match_list_parsing );

            $response = new \Symfony\Component\HttpFoundation\Response( json_encode(array( 'licensees_list' => $licensees_list_parsing )) );
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
        else
        {
            return $this->synchronizationAction();
        }
    }

    public function parseLicenseesFFTTCom( $licensees_list_parsing = array(), $nbPages = 0, $currentIndex = 0, $reqid = "201" ) {

        $moduloPosition = 25;
        
        for( $currentPage = 0; ( empty( $nbPages ) || $currentPage < $nbPages ); $currentPage++ ) {

            $error = FALSE;
            $array_postvars = array();

            $array_postvars["session"] = "";
            $array_postvars["position"] = $currentPage * $moduloPosition;
            $array_postvars["action"] = ( $currentPage == 0 ? "Retour" : "Suite" );
            $array_postvars["reqid"] = $reqid;
            $array_postvars["precision"] = $this->container->getParameter('licensees_fftt.club_number');
            $array_postvars["precision2"] = "";

            $url = $this->container->getParameter('licensees_fftt.uri') . "?";

            foreach( $array_postvars as $key => $value ) {
                $url .= "&" . $key . "=" . $value;
            }

            $ch = curl_init(); // initialize curl handle
            curl_setopt($ch, CURLOPT_URL, $url );
            curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/html'));

            $licensees_fftt_result = curl_exec($ch); // run the whole process

            if ( curl_errno($ch) != 0 ) // CURL error
             $error = TRUE;

            curl_close($ch);

            if( !$error )
            {
                preg_match_all( "#<table(.*)>(.+)</table>#isU", $licensees_fftt_result, $out ); // On récupère seulement le contenu des balises table

                if( empty( $nbPages ) ) {
                    $pagesArray = trim( strip_tags( $out[0][2] ) );
                    $nbPages = explode( "/", $pagesArray );
                    $nbPages = (int) $nbPages[1];
                }

                preg_match_all( "#<tr(.*)>(.+)</tr>#isU", $out[0][1], $out ); // On récupère seulement le contenu des balises tr du tableau spécifié

                foreach( $out[0] as $key => $value ) {
                     preg_match_all( "#<td(.*)>(.+)</td>#isU", $value, $out );

                     if( $key != 0 ) {
                         $i = 0;
                         foreach( $out[0] as $key2 => $elem ) {
                             
                             if( preg_match("#<a href='(.*)'>#", $elem, $out_a_href ) ) {
                                 
                                foreach (explode('&', $out_a_href[1] ) as $chunk) {
                                    $param = explode("=", $chunk);

                                    if ($param) {
                                        
                                        if( urldecode($param[0]) == "precision" ) { // Récupération du N° licence
                                            
                                            $licensees_list_parsing[$currentIndex][DefaultAdminController::$licensees_list_parsing_format[$i]] = (int) urldecode( $param[1] );
                                            $i++;
                                            
                                        }elseif( empty( $this->cler_request_fftt ) && urldecode( $param[0] ) == "cler" ) {  // Récupération du token FFTT permettant d'appeler l'url des matchs
                                            
                                            $this->cler_request_fftt = urldecode( $param[1] );
                                            
                                        }elseif( urldecode($param[0]) == "reqid" ) { // token FFTT permettant de récupérer les matchs masculins (ne sert plus)
                                            if( empty( $this->reqidMen_request_fftt ) && $reqid == $this->container->getParameter('licensees_fftt.reqid.men') ) {
                                                $this->reqidMen_request_fftt = (int) urldecode( $param[1] ); // token FFTT permettant de récupérer les matchs féminins (ne sert plus)
                                            }elseif( empty( $this->reqidWomen_request_fftt ) && $reqid == $this->container->getParameter('licensees_fftt.reqid.women') ) {
                                                $this->reqidWomen_request_fftt = (int) urldecode( $param[1] );
                                            }
                                        }
                                    }
                                }
                             }

                             $final_value = trim( strip_tags( $elem ) );
                             if( $i == 2 ){
                                 $final_value = str_replace( ".", "", $final_value );
                             }elseif( $i == 3 ){
                                 $final_value = utf8_encode( $final_value );
                             }

                             $licensees_list_parsing[$currentIndex][DefaultAdminController::$licensees_list_parsing_format[$i]] = $final_value;
                             $i++;
                         }

                         $licensees_list_parsing[$currentIndex]['title'] = ( $reqid == $this->container->getParameter('licensees_fftt.reqid.men') ? 'M' : 'F' );

                         $currentIndex++;
                    }
                }
            }
            else
            {
                // Synchro terminé, dernière page parsée
            }
        }

        return $licensees_list_parsing;
    }


    public function parseLicenseesMatchsFFTTCom( $licensee_list = array() ){

        $licensees_match_list_parsing = array();
        $moduloPosition = 25;

        foreach( $licensee_list as $licensee ) {

            $currentIndex = 0;
            $nbPages = 1;
            
            for( $currentPage = 0; ( !empty( $nbPages ) && $currentPage < $nbPages ); $currentPage++ ) {

                $error = FALSE;
                $array_postvars = array();

                $array_postvars["position"] = $currentPage * $moduloPosition;
                $array_postvars["action"] = ( $currentPage == 0 ? "Retour" : "Suite" );
                $array_postvars["reqid"] = $licensee[ "title" ] == "M" ? $this->container->getParameter('licensees_fftt.reqid.matchs.men') : $this->container->getParameter('licensees_fftt.reqid.matchs.women');
                $array_postvars["precision"] = $licensee[ "licensee_number" ];
                $array_postvars["cler"] = $this->cler_request_fftt;

                $url = $this->container->getParameter('licensees_matchs_fftt.uri') . "?";

                foreach( $array_postvars as $key => $value ) {
                    $url .= "&" . $key . "=" . $value;
                }

                $ch = curl_init(); // initialize curl handle
                curl_setopt($ch, CURLOPT_URL, $url );
                curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_TIMEOUT, 15);
                curl_setopt($ch, CURLOPT_HEADER, FALSE);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/html'));

                $licensees_matchs_fftt_result = curl_exec($ch); // run the whole process

                if ( curl_errno($ch) != 0 ) // CURL error
                 $error = TRUE;

                curl_close($ch);

                if( !$error )
                {
                    preg_match_all( "#<table(.*)>(.+)</table>#isU", $licensees_matchs_fftt_result, $out ); // On récupère seulement le contenu des balises table

                    if( isset( $out[0][3] ) ) {
                        $pagesArray = trim( strip_tags( $out[0][3] ) );
                        $nbPages = explode( "/", $pagesArray );
                        $nbPages = (int) $nbPages[1];
                    }else{
                        $nbPages = 0;
                    }

                    if( isset( $out[0][3] ) && isset( $out[0][1] ) ) { // Au moins une page trouvée
                        preg_match_all( "#<tr(.*)>(.+)</tr>#isU", $out[0][1], $out ); // On récupère seulement le contenu des balises tr du tableau spécifié

                        foreach( $out[0] as $key => $value ) {

                            preg_match_all( "#<td(.*)>(.+)</td>#isU", $value, $out );

                            if( $key != 0 && $key != 1 ) {

                                foreach( $out[0] as $key2 => $elem ) {
                                    switch($key2){
                                        case 0: $licensees_match_list_parsing[$licensee[ "id" ]][$currentIndex]["position"] = trim( strip_tags( $elem ) ); break;
                                        case 1: $licensees_match_list_parsing[$licensee[ "id" ]][$currentIndex]["category"] = trim( strip_tags( $elem ) ); break;
                                        case 2: $licensees_match_list_parsing[$licensee[ "id" ]][$currentIndex]["datetime_match"] = trim( strip_tags( $elem ) ); break;
                                        case 3:
                                            $name = trim( strip_tags( $elem ) );
                                            $name = explode( " ", $name );
                                            $licensees_match_list_parsing[$licensee[ "id" ]][$currentIndex]["opponent_lastname"] = utf8_encode( $name[0] );
                                            $licensees_match_list_parsing[$licensee[ "id" ]][$currentIndex]["opponent_firstname"] = utf8_encode( $name[1] );

                                            preg_match_all( "#precision%3D(.+)&#isU", $value, $out2 );
                                            if( !empty( $out2[1][0] ) ) {
                                                $licensees_match_list_parsing[$licensee[ "id" ]][$currentIndex]["opponent_licensee_number"] = (int) $out2[1][0]; // N° licence
                                            }

                                        break;
                                        case 4: $licensees_match_list_parsing[$licensee[ "id" ]][$currentIndex]["opponent_ranking"] = trim( strip_tags( $elem ) ); break;
                                        case 5: $licensees_match_list_parsing[$licensee[ "id" ]][$currentIndex]["number_day"] = trim( strip_tags( $elem ) ); break;
                                        case 6: $licensees_match_list_parsing[$licensee[ "id" ]][$currentIndex]["code"] = trim( strip_tags( $elem ) ); break;
                                        case 7: $licensees_match_list_parsing[$licensee[ "id" ]][$currentIndex]["coefficient"] = trim( strip_tags( $elem ) ); break;
                                        case 8: $licensees_match_list_parsing[$licensee[ "id" ]][$currentIndex]["points_evaluation"] = str_replace( array("(", ")", "+"), "", trim( strip_tags( $elem ) ) ); break;
                                    }
                            }
                            
                           
                                $currentIndex++;
                            }
                        }
                    }
                }
                else
                {
                    // Synchro terminé, dernière page parsée
                }
        }
        
         /*echo "<pre>";
         var_dump( $licensee[ "id" ] . "---" );
         if( isset( $licensees_match_list_parsing[$licensee[ "id" ]] ) ) {
            var_dump( $licensees_match_list_parsing[$licensee[ "id" ]] );
        }
        echo "</pre>";*/
                                
    }
    
    //die();

        /*echo "<pre>";
        var_dump( $licensees_match_list_parsing );
        echo "</pre>";*/

        return $licensees_match_list_parsing;
    }


    public function updateLicensees( $licensees_list_parsing ) {
        
        if( !empty( $licensees_list_parsing ) ) {

            try
            {
                // Sauvegarde des licenciés
                $licensee_list = $this->getDoctrine()
                                      ->getRepository('TableTennisLicenseeBundle:Licensee')
                                      ->getLicensees()
                                      ->getArrayResult();

                $em = $this->getDoctrine()->getEntityManager();

                $tmp_licensees_list_parsing = $licensees_list_parsing;

                foreach( $licensee_list as $key => $value ) {

                    $findLicensee = FALSE;

                    foreach( $tmp_licensees_list_parsing as $key2 => $value2 ) {

                        // Mise à jour du licencié
                        if( $value2["licensee_number"] == $value[ "licensee_number" ] ) {

                            $licensee = $em->getRepository('TableTennisLicenseeBundle:Licensee')->find( $value[ "id" ] );
                            $licensee->setLastname( $value2["lastname"] );
                            $licensee->setFirstname( $value2["firstname"] );
                            $licensee->setRanking( $value2["ranking"] );
                            $licensee->setCategory( $value2["category"] );
                            $licensee->setTitle( $value2["title"] );

                            $findLicensee = TRUE;
                            unset( $tmp_licensees_list_parsing[ $key2 ] ); // supression dans le tableau pour ne pas l'insérer plus tard
                        }
                    }

                    // Suppression du licencié
                    if( !$findLicensee ) {
                        $licensee = $em->getRepository('TableTennisLicenseeBundle:Licensee')->find( $value[ "id" ] );
                        $em->remove( $licensee );
                    }
                }

                // Ajout des licenciés
                foreach( $tmp_licensees_list_parsing as $key => $value ) {
                    $licensee = new Licensee();
                    $licensee->setLicenseeNumber( $value["licensee_number"] );
                    $licensee->setLastname( $value["lastname"] );
                    $licensee->setFirstname( $value["firstname"] );
                    $licensee->setRanking( $value["ranking"] );
                    $licensee->setNbCurrentPoints( $value["nb_mensual_points"] );
                    $licensee->setCategory( $value["category"] );
                    $licensee->setMonthlyIncrease( 0 );

                    $em->persist($licensee);
                }

                $em->flush();
            }catch( Exception $e ){

            }

            try
            {
                $em = $this->getDoctrine()->getEntityManager();

                // Sauvegarde des points des licenciés
                foreach( $licensee_list as $key => $value ) {

                    foreach( $licensees_list_parsing as $key2 => $value2 ) {

                        // Mise à jour du licencié
                        if( $value2["licensee_number"] == $value[ "licensee_number" ] ) {

                            $currentDate = new \DateTime();
                            $currentDate->setDate( date('Y'), date('m'), '01'); // Premier jour du mois en cours
                            $currentDate->setTime(0, 0, 0);

                            $licensee = $em->getRepository('TableTennisLicenseeBundle:Licensee')->find( $value[ "id" ] );

                            $licenseePoint = $this->getDoctrine()
                                                  ->getRepository('TableTennisLicenseeBundle:LicenseePoint')
                                                  ->findBy(array('licensee_id' => $value[ "id" ],
                                                                 'datetime_points' => $currentDate ));

                            $updateMonthlyIncrease = FALSE;
                            if( empty( $licenseePoint ) ){
                                $licenseePoint = new LicenseePoint();
                                
                                $licenseePoint->setLicenseeId( $licensee );
                                $licenseePoint->setDatetimePoints( $currentDate );

                                $updateMonthlyIncrease = TRUE;
                            }else{
                                $licenseePoint = $licenseePoint[0];
                                $updateMonthlyIncrease = ( $licenseePoint->getNbPointsFftt() != $value2["nb_mensual_points"] );
                            }
                            
                            $licenseePoint->setNbPointsFftt( $value2["nb_mensual_points"] );

                            $em->persist($licenseePoint);

                            if( $updateMonthlyIncrease ) {
                                // Mise à jour de la progression mensuelle
                                $monthly_increase = 0;
                                if( !empty( $value2["nb_mensual_points"] ) )
                                {
                                    $nb_current_points = $licensee->getNbCurrentPoints();
                                    $nb_current_points = !is_null( $nb_current_points ) ? $nb_current_points : 0;
                                    $monthly_increase = $value2["nb_mensual_points"] - $nb_current_points;
                                }

                                $licensee->setMonthlyIncrease( $monthly_increase );
                                $licensee->setNbCurrentPoints( $value2["nb_mensual_points"] );

                                $em->persist($licensee);
                            }
                        }
                    }
                }

                $em->flush();
            }catch(Exception $e){
                // var_dump( $e );
            }
        }
    }


    public function updateLicenseesMatchs( $licensees_match_list_parsing ){

        if( !empty( $licensees_match_list_parsing ) ) {

            try
            {
                foreach( $licensees_match_list_parsing as $licensee_id => $licensee_value ) {

                    foreach( $licensee_value as $key => $value ) {

                        $em = $this->getDoctrine()->getEntityManager();

                        $datetime_match_explode = explode( "/", $value[ "datetime_match" ] );

                        $datetime_match = new \DateTime();
                        $datetime_match->setDate("20" . $datetime_match_explode[2], $datetime_match_explode[1], $datetime_match_explode[0]);
                        $datetime_match->setTime(0, 0, 0);

                        $licenseeMatch = $this->getDoctrine()
                                              ->getRepository('TableTennisLicenseeBundle:LicenseeMatch')
                                              ->findBy(array('opponent_licensee_number' => $value[ "opponent_licensee_number" ],
                                                             'datetime_match' => $datetime_match,
                                                             'opponent_position' => $value[ "position" ] ));

                        if( empty( $licenseeMatch ) ){
                            $licenseeMatch = new LicenseeMatch();
                        }else{
                            $licenseeMatch = $licenseeMatch[0];
                        }

                        $licensee = $em->getRepository('TableTennisLicenseeBundle:Licensee')->find( $licensee_id );

                        $licenseeMatch->setCategory( $value[ "category" ] );
                        $licenseeMatch->setCode( $value[ "code" ] );
                        $licenseeMatch->setCoefficient( $value[ "coefficient" ] );
                        $licenseeMatch->setDatetimeMatch( $datetime_match );
                        $licenseeMatch->setLicenseeId( $licensee );
                        //$licenseeMatch->setMatchTypeId();
                        $licenseeMatch->setNumberDay( $value[ "number_day" ] );
                        $licenseeMatch->setOpponentFirstname( $value[ "opponent_firstname" ] );
                        $licenseeMatch->setOpponentLastname( $value[ "opponent_lastname" ] );
                        $licenseeMatch->setOpponentLicenseeNumber( $value[ "opponent_licensee_number" ] );
                        $licenseeMatch->setOpponentPoint( null );
                        $licenseeMatch->setOpponentPosition( $value[ "position" ] );
                        $licenseeMatch->setOpponentRanking( $value[ "opponent_ranking" ] );
                        $licenseeMatch->setPointsEvaluation( $value[ "points_evaluation" ] );

                        $em->persist($licenseeMatch);
                        
                    }

                }

                $em->flush();


            }catch( Exception $e ){
                // var_dump( $e );
            }
        }
    }
}