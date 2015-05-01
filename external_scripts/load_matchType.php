<?php

    echo 'Tentative de connexion ...';
    $myDb = new DB;
    $myDb->connect();

    $query = "SELECT SQL_CALC_FOUND_ROWS `match_type`.* "
           . "FROM `". MYSQL_DBNAME_WEBSITE. "`.`match_type` match_type ";

     if( ( $result = $myDb->query( $query ) ) !== FALSE ) {

         if( $result->num_rows > 0 ) {
            $dataList = array();
            while( $row = $result->fetch_object() ){
                $dataList[] = array(
                                    "id" => $row->id,
                                    "coefficient" => $row->coefficient,
                                    "title" => utf8_encode( $row->title ),
                                    "type" => $row->type
                                    );
            }
         }
     }

?>