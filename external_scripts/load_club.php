<?php

    echo 'Tentative de connexion ...';
    $myDb = new DB;
    $myDb->connect();

    $query = "SELECT SQL_CALC_FOUND_ROWS `club`.* "
           . "FROM `". MYSQL_DBNAME_WEBSITE. "`.`club` club ";

     if( ( $result = $myDb->query( $query ) ) !== FALSE ) {

         if( $result->num_rows > 0 ) {
            $dataList = array();
            while( $row = $result->fetch_object() ){
                $dataList[] = array(
                                    "id" => $row->id,
                                    "name" => utf8_encode( $row->name ),
                                    "city_id" => utf8_encode( $row->city_id ),
                                    "club_number" => $row->club_number,
                                    "website" => $row->website,
                                    "slug" => $row->slug,
                                    "created_at" => $row->created_at,
                                    "updated_at" => $row->updated_at
                                    );
            }
         }
     }

?>