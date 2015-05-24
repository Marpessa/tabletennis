#!/usr/bin/php
<?php

$error = FALSE;

$array_postvars = array();

$ch = curl_init(); // initialize curl handle
curl_setopt($ch, CURLOPT_URL, "http://" .  $_SERVER['SERVER_NAME'] . "/cron_import_fftt_data.html" );
curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
//curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
curl_setopt($ch, CURLOPT_POSTFIELDS, implode('&', $array_postvars));
$feeds_fftt_com_result = curl_exec($ch); // run the whole process

if ( curl_errno($ch) != 0 ) // CURL error
 $error = TRUE;

if( !$error )
{
	echo "OK";
}
else
{
    echo "error";
    //var_dump( curl_error($ch) );
}
curl_close($ch);

 /*
 ----- Test 2

 header('Location: http://www.cpfaizenay.com/cron_import_fftt_data.html');
 exit;

----- Test 3

*/

 /*
 #!/bin/bash
cd /home/cpfaizen
php.ORIG.5_4 -c /usr/local/lib/php.ini-2 /home/cpfaizen/app/console cron:fftt

*/
?>