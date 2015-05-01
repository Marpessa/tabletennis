<?php

    echo 'Tentative de connexion ...';
    $myDb = new DB;
    $myDb->connect();

    $query = "SELECT SQL_CALC_FOUND_ROWS `drift`.* "
           . "FROM `". MYSQL_DBNAME_WEBSITE. "`.`drift` drift ";

     if( ( $result = $myDb->query( $query ) ) !== FALSE ) {

         if( $result->num_rows > 0 ) {
            $dataList = array();
            while( $row = $result->fetch_object() ){
                $dataList[] = array(
                                    "id" => $row->id,
                                    "value" => utf8_encode( $row->value ),
                                    "datetime_drift" => utf8_encode( $row->datetime_drift )
                                    );
            }
         }
     }

?>