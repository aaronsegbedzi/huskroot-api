<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/include/v1/config.php');

// Connect to the database.
	try {
		$conn = new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
		$conn->set_charset("utf8mb4");
	} catch (Exception $e) {
		throw $e;
		exit;
	}
// Clean string of whitespaces.
	function clean_input($string){
		trim($string);
		return $string;
	}
// Prevent SQL injection.
	function secure_input($conn, $variable){
		$variable = clean_input($variable);
		$variable = mysqli_real_escape_string($conn, $variable);
		return $variable;
	}
// Format string.
	function format_input($string, $case){
		switch ($case) {
			case 'upper':
					$string = strtoupper($string);
					return $string;
				break;
			case 'lower':
					$string = strtolower($string);
					return $string;
				break;
			case 'sentence':
					$string = strtolower($string);
					$string = ucfirst($string);
					return $string;
				break;
			default:
					return false;
				break;
		}
	}
?>