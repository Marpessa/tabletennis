<?php

    $myDb = new DB;
    $myDb->connect();

    $query = "SELECT SQL_CALC_FOUND_ROWS `sf_simple_forum_post`.* "
           . "FROM `". MYSQL_DBNAME_WEBSITE. "`.`sf_simple_forum_post` sf_simple_forum_post ";

     if( ( $result = $myDb->query( $query ) ) !== FALSE ) {

         if( $result->num_rows > 0 ) {
            $dataList = array();
            while( $row = $result->fetch_object() ){
                $dataList[] = array(
                                    "id" => $row->id,
                                    "title" => utf8_encode( $row->title ),
                                    "content" => utf8_encode( $row->content ),
                                    "topic_id" => utf8_encode( $row->topic_id ),
                                    "user_id" => utf8_encode( $row->user_id ),
                                    "forum_id" => utf8_encode( $row->forum_id ),
                                    "author_name" => utf8_encode( $row->author_name ),
                                    "created_at" => $row->created_at,
                                    "updated_at" => $row->updated_at
                                    );
            }
         }
     }

?>