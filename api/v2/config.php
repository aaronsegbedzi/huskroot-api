<?php

	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
	header("Access-Control-Allow-Headers: *");

	date_default_timezone_set('Africa/Accra');
	
	define('DB_HOST', 'db709871838.db.1and1.com');
	define('DB_USERNAME', 'dbo709871838');
	define('DB_PASSWORD', 'Huskroot@123456');
	define('DB_NAME', 'db709871838');

?>