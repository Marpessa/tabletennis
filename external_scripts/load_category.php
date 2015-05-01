<?php

    echo 'Tentative de connexion ...';
    $myDb = new DB;
    $myDb->connect();

    $query = "SELECT SQL_CALC_FOUND_ROWS `category`.* "
           . "FROM `". MYSQL_DBNAME_WEBSITE. "`.`category` category ";

     if( ( $result = $myDb->query( $query ) ) !== FALSE ) {

         if( $result->num_rows > 0 ) {
            $dataList = array();
            while( $row = $result->fetch_object() ){
                $dataList[] = array(
                                    "id" => $row->id,
                                    "title" => utf8_encode( $row->title ),
                                    "creation_user_id" => $row->creation_user_id,
                                    "modification_user_id" => $row->modification_user_id,
                                    "type" => utf8_encode( $row->type ),
                                    "category_parent_id" => $row->category_parent_id,
                                    "link" => $row->link,
                                    "num_order" => $row->num_order,
                                    "publication" => $row->publication,
                                    "slug" => $row->slug,
                                    "created_at" => $row->created_at,
                                    "updated_at" => $row->updated_at
                                    );
            }
         }
     }

?>