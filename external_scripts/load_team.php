<?php

    echo 'Tentative de connexion ...';
    $myDb = new DB;
    $myDb->connect();

    $query = "SELECT SQL_CALC_FOUND_ROWS `team`.*, "
           . " sf_asset.filename AS asset_filename, sf_asset.updated_at AS asset_updated_at "
           . "FROM `". MYSQL_DBNAME_WEBSITE. "`.`team` team "
           . "LEFT JOIN `". MYSQL_DBNAME_WEBSITE. "`.`sf_asset` sf_asset ON team.asset_id = sf_asset.id ";

     if( ( $result = $myDb->query( $query ) ) !== FALSE ) {

         if( $result->num_rows > 0 ) {

            $dataList = array();

            while( $row = $result->fetch_object() ){
                $dataList[] = array(
                                    "id" => $row->id,
                                    "name" => utf8_encode( $row->name ),
                                    "content" => utf8_encode( $row->content ),
                                    "team_category" => $row->team_category,
                                    "creation_user_id" => $row->creation_user_id,
                                    "modification_user_id" => $row->modification_user_id,
                                    "num_order" => $row->num_order,
                                    "asset_id" => $row->asset_id,
                                    "created_at" => $row->created_at,
                                    "updated_at" => $row->updated_at,
                                    "asset" => array( "filename" => $row->asset_filename, "updated_at" => $row->asset_updated_at )
                                    );
            }
         }
     }

?>