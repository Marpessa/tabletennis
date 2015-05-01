<?php

    echo 'Tentative de connexion ...';
    $myDb = new DB;
    $myDb->connect();

    $query = "SELECT SQL_CALC_FOUND_ROWS `licensee_point`.* "
           . "FROM `". MYSQL_DBNAME_WEBSITE. "`.`licensee_point` licensee_point "
           . "ORDER BY datetime_points ASC;";

     if( ( $result = $myDb->query( $query ) ) !== FALSE ) {

         if( $result->num_rows > 0 ) {
            $dataList = array();
            while( $row = $result->fetch_object() ){
                $dataList[] = array(
                                    "id" => $row->id,
                                    "licensee_id" => $row->licensee_id,
                                    "creation_user_id" => $row->creation_user_id,
                                    "modification_user_id" => $row->modification_user_id,
                                    "nb_points" => $row->nb_points,
                                    "nb_points_fftt" => $row->nb_points_fftt,
                                    "datetime_points" => $row->datetime_points,
                                    "created_at" => $row->created_at,
                                    "updated_at" => $row->updated_at
                                    );
            }
         }
     }

?>