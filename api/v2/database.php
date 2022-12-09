<?php
	
	require_once($_SERVER['DOCUMENT_ROOT'].'/api/v2/config.php');

	$conn = mysqli_connect(
		DB_HOST,
		DB_USERNAME,
		DB_PASSWORD,
		DB_NAME
	) or die(http_response_code(503));

	function clean_input($string){
		trim($string);
		return $string;
	}

	function secure_input($conn, $variable){
		$variable = clean_input($variable);
		$variable = mysqli_real_escape_string($conn, $variable);
		return $variable;
	}

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