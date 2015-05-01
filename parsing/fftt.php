<?php

class ParsingFFtt
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

	private static $licensees_fftt_uri = "http://www.fftt.com/sportif/pclassement/php3/FFTTlj.php3";
	private static $licensees_fftt_reqid_men = 211;
	private static $licensees_fftt_reqid_women = 311;

	private static $licensees_matchs_fftt_uri = "http://www.fftt.com/sportif/pclassement/php3/FFTTlp.php3";
    private static $licensees_fftt_reqid_matchs_men = 400;
	private static $licensees_fftt_reqid_matchs_women = 500;
   	private static $licensees_fftt_club_number = "04850028";

	public function synchronizationAction()
	{
		$licensees_list_parsing = array();
		$licensees_list_parsing = $this->parseLicenseesFFTTCom( $licensees_list_parsing, 0, 0, ParsingFFtt::$licensees_fftt_reqid_men );
		$licensees_list_parsing = $this->parseLicenseesFFTTCom( $licensees_list_parsing, 0, count($licensees_list_parsing), ParsingFFtt::$licensees_fftt_reqid_women );
		
		$licensees_match_list_parsing = array();
        $licensees_match_list_parsing = $this->parseLicenseesMatchsFFTTCom( $licensees_list_parsing );
		/*
		echo "<pre>";
		var_dump( $licensees_match_list_parsing );
		echo "</pre>";
		die();*/

		$date = new Datetime();
		$fp = fopen( __DIR__ . '/../www/parsing_results/parsing_fftt_' . $date->format('Y') . '_' . $date->format('m')  . '.csv', 'w+');

		foreach( $licensees_list_parsing as $licensee )
		{
			fputcsv($fp, array('N°licence', 'Nom', 'Prénom', 'Classement', 'Nombre de points', 'Catégorie', 'Sexe'), ";" );

			fputcsv($fp, array(   $licensee["licensee_number"],  
							utf8_encode( $licensee["lastname"] ),
							utf8_encode( $licensee["firstname"] ),
							$licensee["ranking"],
							$licensee["nb_mensual_points"],
							$licensee["category"],
							$licensee["title"]
					  ), ";" );

			fputcsv($fp, array(''), ";" );
			fputcsv($fp, array('Matchs :'), ";" );
			fputcsv($fp, array('Position', 'Victoire/Défaite', 'Date du match', 'Nom', 'Prénom', 'N°licence', 'Classement', 'N°jour', 'Code du match', 'Coefficient', 'Gain/Perte'), ";" );
			foreach( $licensees_match_list_parsing as $licensee_number => $licensee_match )
			{
				if( $licensee_number == $licensee['licensee_number'] )
				{
					foreach( $licensee_match as $match )
					{
						fputcsv($fp, array(   $match["position"],  
										$match["category"],
									        $match["datetime_match"],
										utf8_encode( $match["opponent_lastname"] ),
										utf8_encode( $match["opponent_firstname"] ),
										$match["opponent_licensee_number"],
										$match["opponent_ranking"],
										$match["number_day"],
										$match["code"],
										$match["coefficient"],
										$match["points_evaluation"]
					  ), ";" );
					}
				}
			}
			fputcsv($fp, array(), ";");
			fputcsv($fp, array('########', '########', '########', '########', '########', '########', '########'), ";");
			fputcsv($fp, array(), ";");
		}

		fclose($fp);

		echo "Cliquez ici pour t&eacute;l&eacute;charger le fichier : <a target='_blank' download='parsing_fftt_" . $date->format('Y') . "_" . $date->format('m')  . ".csv' href='http://www.cpfaizenay.com/parsing_results/parsing_fftt_" . $date->format('Y') . "_" . $date->format('m')  . ".csv'>parsing_fftt_" . $date->format('Y') . "_" . $date->format('m')  . ".csv</a>";
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
            $array_postvars["precision"] = ParsingFFtt::$licensees_fftt_club_number;
            $array_postvars["precision2"] = "";

            $url = ParsingFFtt::$licensees_fftt_uri . "?";

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
                                            
                                            $licensees_list_parsing[$currentIndex][ParsingFFtt::$licensees_list_parsing_format[$i]] = (int) urldecode( $param[1] );
                                            $i++;
                                            
                                        }elseif( empty( $this->cler_request_fftt ) && urldecode( $param[0] ) == "cler" ) {  // Récupération du token FFTT permettant d'appeler l'url des matchs
                                            
                                            $this->cler_request_fftt = urldecode( $param[1] );
                                            
                                        }elseif( urldecode($param[0]) == "reqid" ) { // token FFTT permettant de récupérer les matchs masculins (ne sert plus)
                                            if( empty( $this->reqidMen_request_fftt ) && $reqid == ParsingFFtt::$licensees_fftt_reqid_men ) {
                                                $this->reqidMen_request_fftt = (int) urldecode( $param[1] ); // token FFTT permettant de récupérer les matchs féminins (ne sert plus)
                                            }elseif( empty( $this->reqidWomen_request_fftt ) && $reqid == ParsingFFtt::$licensees_fftt_reqid_women ) {
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

                             $licensees_list_parsing[$currentIndex][ParsingFFtt::$licensees_list_parsing_format[$i]] = $final_value;
                             $i++;
                         }

                         $licensees_list_parsing[$currentIndex]['title'] = ( $reqid == ParsingFFtt::$licensees_fftt_reqid_men ? 'M' : 'F' );

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
                $array_postvars["reqid"] = $licensee["title"] == "M" ? ParsingFFtt::$licensees_fftt_reqid_matchs_men : ParsingFFtt::$licensees_fftt_reqid_matchs_women;
                $array_postvars["precision"] = $licensee[ "licensee_number" ];
                $array_postvars["cler"] = $this->cler_request_fftt;

                $url = ParsingFFtt::$licensees_matchs_fftt_uri . "?";

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
                                        case 0: $licensees_match_list_parsing[$licensee[ "licensee_number" ]][$currentIndex]["position"] = trim( strip_tags( $elem ) ); break;
                                        case 1: $licensees_match_list_parsing[$licensee[ "licensee_number" ]][$currentIndex]["category"] = trim( strip_tags( $elem ) ); break;
                                        case 2: $licensees_match_list_parsing[$licensee[ "licensee_number" ]][$currentIndex]["datetime_match"] = trim( strip_tags( $elem ) ); break;
                                        case 3:
                                            $name = trim( strip_tags( $elem ) );
                                            $name = explode( " ", $name );
                                            $licensees_match_list_parsing[$licensee[ "licensee_number" ]][$currentIndex]["opponent_lastname"] = $name[0];
                                            $licensees_match_list_parsing[$licensee[ "licensee_number" ]][$currentIndex]["opponent_firstname"] = $name[1];

                                            preg_match_all( "#precision%3D(.+)&#isU", $value, $out2 );
                                            if( !empty( $out2[1][0] ) ) {
                                                $licensees_match_list_parsing[$licensee[ "licensee_number" ]][$currentIndex]["opponent_licensee_number"] = (int) $out2[1][0]; // N° licence
                                            }

                                        break;
                                        case 4: $licensees_match_list_parsing[$licensee[ "licensee_number" ]][$currentIndex]["opponent_ranking"] = trim( strip_tags( $elem ) ); break;
                                        case 5: $licensees_match_list_parsing[$licensee[ "licensee_number" ]][$currentIndex]["number_day"] = trim( strip_tags( $elem ) ); break;
                                        case 6: $licensees_match_list_parsing[$licensee[ "licensee_number" ]][$currentIndex]["code"] = trim( strip_tags( $elem ) ); break;
                                        case 7: $licensees_match_list_parsing[$licensee[ "licensee_number" ]][$currentIndex]["coefficient"] = trim( strip_tags( $elem ) ); break;
                                        case 8: $licensees_match_list_parsing[$licensee[ "licensee_number" ]][$currentIndex]["points_evaluation"] = str_replace( array("(", ")", "+"), "", trim( strip_tags( $elem ) ) ); break;
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
        }

        /*echo "<pre>";
        var_dump( $licensees_match_list_parsing );
        echo "</pre>";*/

        return $licensees_match_list_parsing;
    }

}

$parsingFFtt = new ParsingFFtt();
$parsingFFtt->synchronizationAction();


?>
