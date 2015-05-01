<?php

    echo 'Tentative de connexion ...';
    $myDb = new DB;
    $myDb->connect();

    $query = "SELECT SQL_CALC_FOUND_ROWS `comment`.* "
           . "FROM `". MYSQL_DBNAME_WEBSITE. "`.`comment` comment ";

     if( ( $result = $myDb->query( $query ) ) !== FALSE ) {

         if( $result->num_rows > 0 ) {
            $dataList = array();
            while( $row = $result->fetch_object() ){
                $dataList[] = array(
                                    "id" => $row->id,
                                    "record_model" => $row->record_model,
                                    "record_id" => $row->record_id,
                                    "record_slug" => $row->record_slug,
                                    "author_name" => utf8_encode( $row->author_name ),
                                    "author_email" => utf8_encode( $row->author_email ),
                                    "body" => utf8_encode( $row->body ),
                                    "is_delete" => $row->is_delete,
                                    "user_id" => $row->user_id,
                                    "created_at" => $row->created_at,
                                    "updated_at" => $row->updated_at
                                    );
            }
         }
     }

?>