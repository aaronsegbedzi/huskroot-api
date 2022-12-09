<?php
// Database configuration settings.
	// Set MySQL database host.
	define('DB_HOST', 'localhost');
	// Set MySQL database connection username.
	define('DB_USERNAME', 'root');
	// Set MySQL database connection password.
	define('DB_PASSWORD', '');
	// Set MySQL database connection _database name.
	define('DB_NAME', 'huskroot');

// Error Messages

	define('ERROR_DEFAULT', 'The system is temporarily unavailable.');

	define('ERROR_REQUEST_METHOD', 'The request method is not supported.');

	define('ERROR_LOGIN_VALUES', 'Login Failure: Username and password is required to complete authentication.');

	define('ERROR_INVALID_USERNAME', 'Login Failure: The target account name is incorrect or does not exist.');

	define('ERROR_INVALID_PASSWORD', 'Login Failure: The specified account username or password is not correct.');


// Success Messages
	define('REPLY_LOGIN_SUCCESS', 'Successfully logged in. Redirecting to the dashboard...');
	

?>