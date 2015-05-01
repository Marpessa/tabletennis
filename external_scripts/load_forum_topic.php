<?php

    $myDb = new DB;
    $myDb->connect();

    $query = "SELECT SQL_CALC_FOUND_ROWS `sf_simple_forum_topic`.* "
           . "FROM `". MYSQL_DBNAME_WEBSITE. "`.`sf_simple_forum_topic` sf_simple_forum_topic ";

     if( ( $result = $myDb->query( $query ) ) !== FALSE ) {

         if( $result->num_rows > 0 ) {
            $dataList = array();
            while( $row = $result->fetch_object() ){
                $dataList[] = array(
                                    "id" => $row->id,
                                    "title" => utf8_encode( $row->title ),
                                    "is_sticked" => utf8_encode( $row->is_sticked ),
                                    "is_locked" => utf8_encode( $row->is_locked ),
                                    "forum_id" => utf8_encode( $row->forum_id ),
                                    "latest_post_id" => utf8_encode( $row->latest_post_id ),
                                    "user_id" => utf8_encode( $row->user_id ),
                                    "nb_posts" => utf8_encode( $row->nb_posts ),
                                    "nb_views" => utf8_encode( $row->nb_views ),
                                    "nb_recommandations" => utf8_encode( $row->nb_recommandations ),
                                    
                                    "slug" => $row->slug,
                                    "created_at" => $row->created_at,
                                    "updated_at" => $row->updated_at
                                    );
            }
         }
     }

?>