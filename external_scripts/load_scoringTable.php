<?php

    echo 'Tentative de connexion ...';
    $myDb = new DB;
    $myDb->connect();

    $query = "SELECT SQL_CALC_FOUND_ROWS `scoring_table`.* "
           . "FROM `". MYSQL_DBNAME_WEBSITE. "`.`scoring_table` scoring_table ";

     if( ( $result = $myDb->query( $query ) ) !== FALSE ) {

         if( $result->num_rows > 0 ) {
            $dataList = array();
            while( $row = $result->fetch_object() ){
                $dataList[] = array(
                                    "id" => $row->id,
                                    "points_away" => $row->points_away,
                                    "normal_victory" => $row->normal_victory,
                                    "normal_defeat" => $row->normal_defeat,
                                    "anormal_victory" => $row->anormal_victory,
                                    "anormal_defeat" => $row->anormal_defeat
                                    );
            }
         }
     }

?>