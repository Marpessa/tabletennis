<?php

    echo 'Tentative de connexion ...';
    $myDb = new DB;
    $myDb->connect();

    $query = "SELECT SQL_CALC_FOUND_ROWS `licensee`.* "
           . "FROM `". MYSQL_DBNAME_WEBSITE. "`.`licensee` licensee ";

     if( ( $result = $myDb->query( $query ) ) !== FALSE ) {

         if( $result->num_rows > 0 ) {
            $dataList = array();
            while( $row = $result->fetch_object() ){
                $dataList[] = array(
                                    "id" => $row->id,
                                    "licensee_user_id" => $row->licensee_user_id,
                                    "licensee_number" => $row->licensee_number,
                                    "lastname" => utf8_encode( $row->lastname ),
                                    "firstname" => utf8_encode( $row->firstname ),
                                    "category" => $row->category,
                                    "status" => $row->status,
                                    "team_id" => $row->team_id,
                                    "club_id" => $row->club_id,
                                    "slug" => $row->slug,
                                    "created_at" => $row->created_at,
                                    "updated_at" => $row->updated_at
                                    );
            }
         }
     }

?>