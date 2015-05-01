<?php

    $myDb = new DB;
    $myDb->connect();

    $query = "SELECT SQL_CALC_FOUND_ROWS `sf_simple_forum_forum`.* "
           . "FROM `". MYSQL_DBNAME_WEBSITE. "`.`sf_simple_forum_forum` sf_simple_forum_forum ";

     if( ( $result = $myDb->query( $query ) ) !== FALSE ) {

         if( $result->num_rows > 0 ) {
            $dataList = array();
            while( $row = $result->fetch_object() ){
                $dataList[] = array(
                                    "id" => $row->id,
                                    "name" => utf8_encode( $row->name ),
                                    "description" => utf8_encode( $row->description ),
                                    "rank" => $row->rank,
                                    "category_id" => $row->category_id,
                                    "latest_post_id" => $row->latest_post_id,
                                    "nb_posts" => $row->nb_posts,
                                    "nb_topics" => $row->nb_topics,
                                    
                                    "slug" => $row->slug,
                                    "created_at" => $row->created_at,
                                    "updated_at" => $row->updated_at
                                    );
            }
         }
     }

?>