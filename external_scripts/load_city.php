<?php

    echo 'Tentative de connexion ...';
    $myDb = new DB;
    $myDb->connect();

    $query = "SELECT SQL_CALC_FOUND_ROWS `city`.* "
           . "FROM `". MYSQL_DBNAME_WEBSITE. "`.`city` city ";

     if( ( $result = $myDb->query( $query ) ) !== FALSE ) {

         if( $result->num_rows > 0 ) {
            $dataList = array();
            while( $row = $result->fetch_object() ){
                $dataList[] = array(
                                    "id" => $row->id,
                                    "code" => $row->code,
                                    "iso" => $row->iso,
                                    "name" => utf8_encode( $row->name ),
                                    "rawname" => utf8_encode( $row->rawname ),
                                    "longitude_radian" => $row->longitude_radian,
                                    "latitude_radian" => $row->latitude_radian,
                                    "maintown_id" => $row->maintown_id,
                                    "department_id" => $row->department_id,
                                    "region_id" => $row->region_id,
                                    "country_id" => $row->country_id
                                    );
            }
         }
     }

?>