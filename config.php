<?php
//DB credentials
$username 	= 'melissa_blog';
$password 	= 'dsvDxujVNcNd4H6u';
$host 		= 'localhost';
$database 	= 'melissa_blog_1115';

//connect
$db = new mysqli( $host, $username, $password, $database );

//show an error message and stop the page from displaying if there is a problem connecting
if( $db->connect_errno > 0 ){
	die('Unable to connect to the Database.');
}

//error reporting
error_reporting( E_ALL & ~E_NOTICE ); 

//try to fix weird characters
$db->set_charset("utf8");


//no close PHP