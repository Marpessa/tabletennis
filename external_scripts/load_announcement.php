<?php

    echo 'Tentative de connexion ...';
    $myDb = new DB;
    $myDb->connect();

    $query = "SELECT SQL_CALC_FOUND_ROWS `announcement`.* "
           . "FROM `". MYSQL_DBNAME_WEBSITE. "`.`announcement` announcement ";

     if( ( $result = $myDb->query( $query ) ) !== FALSE ) {

         if( $result->num_rows > 0 ) {
            $dataList = array();
            while( $row = $result->fetch_object() ){
                $dataList[] = array(
                                    "id" => $row->id,
                                    "title" => utf8_encode( $row->title ),
                                    "description" => utf8_encode( $row->description ),
                                    "creation_user_id" => $row->creation_user_id,
                                    "modification_user_id" => $row->modification_user_id,
                                    "type" => $row->type,
                                    "slug" => $row->slug,
                                    "created_at" => $row->created_at,
                                    "updated_at" => $row->updated_at
                                    );
            }
         }
     }

?>