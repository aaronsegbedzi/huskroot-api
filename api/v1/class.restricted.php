<?php 
	// Start session.
	session_start();
	// Check if there are session variables set.
	if (!isset($_SESSION['username']) && $scriptName != 'index'){
		// // Redirect to login page.
		header("Location: /");
		exit();
	}else if (isset($_SESSION['username']) && $scriptName == 'index') {
		// Redirect to welcome page.
		header("Location: /explore");
		exit();
	}else if (isset($_SESSION['username']) && $_SESSION['change_password'] == true && $scriptName != 'password') {
		// Redirect to password page.
		header("Location: /password");
		exit();
	}
?>