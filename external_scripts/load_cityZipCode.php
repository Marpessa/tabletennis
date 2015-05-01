<?php

    echo 'Tentative de connexion ...';
    $myDb = new DB;
    $myDb->connect();

    $query = "SELECT SQL_CALC_FOUND_ROWS `city_zipCode`.* "
           . "FROM `". MYSQL_DBNAME_WEBSITE. "`.`city_zipCode` city_zipCode ";

     if( ( $result = $myDb->query( $query ) ) !== FALSE ) {

         if( $result->num_rows > 0 ) {
            $dataList = array();
            while( $row = $result->fetch_object() ){
                $dataList[] = array(
                                    "city_id" => $row->city_id,
                                    "zip_code" => $row->zip_code
                                    );
            }
         }
     }

?>