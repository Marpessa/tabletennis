<?php

    echo 'Tentative de connexion ...';
    $myDb = new DB;
    $myDb->connect();

    $query = "SELECT SQL_CALC_FOUND_ROWS `country`.* "
           . "FROM `". MYSQL_DBNAME_WEBSITE. "`.`country` country ";

     if( ( $result = $myDb->query( $query ) ) !== FALSE ) {

         if( $result->num_rows > 0 ) {
            $dataList = array();
            while( $row = $result->fetch_object() ){
                $dataList[] = array(
                                    "id" => $row->id,
                                    "code" => $row->code,
                                    "iso" => $row->iso,
                                    "name" => utf8_encode( $row->name )
                                    );
            }
         }
     }

?>