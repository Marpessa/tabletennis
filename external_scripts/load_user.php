<?php
    echo 'Tentative de connexion ...';
    $myDb = new DB;
    $myDb->connect();
    
    $query = "SELECT SQL_CALC_FOUND_ROWS `sf_guard_user`.*, "
           . " sf_asset.filename AS asset_filename, sf_asset.updated_at AS asset_updated_at "
           . "FROM `". MYSQL_DBNAME_WEBSITE. "`.`sf_guard_user` sf_guard_user "
           . "LEFT JOIN `". MYSQL_DBNAME_WEBSITE. "`.`sf_asset` sf_asset ON sf_guard_user.asset_id = sf_asset.id ";

     if( ( $result = $myDb->query( $query ) ) !== FALSE ) {

         if( $result->num_rows > 0 ) {

            $dataList = array();

            while( $row = $result->fetch_object() ){
                $dataList[] = array(
                                    "id" => $row->id,
                                    "type" => $row->type,
                                    "first_name" => utf8_encode( $row->first_name ),
                                    "last_name" => utf8_encode( $row->last_name ),
                                    "email_address" => $row->email_address,
                                    "username" => utf8_encode( $row->username ),
                                    "algorithm" => $row->algorithm,
                                    "salt" => $row->salt,
                                    "password" => $row->password,
                                    "is_active" => $row->is_active,
                                    "is_super_admin" => $row->is_super_admin,
                                    "last_login" => $row->last_login,
                                    "is_delete" => $row->is_delete,
                                    "asset_id" => $row->asset_id,
                                    "created_at" => $row->created_at,
                                    "updated_at" => $row->updated_at,
                                    "asset" => array( "filename" => $row->asset_filename, "updated_at" => $row->asset_updated_at )
                                    );
            }
         }
     }

?>