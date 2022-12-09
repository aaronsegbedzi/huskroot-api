<?php
	function startSession($array){
		if ($array) {
			// Start session.
			session_start();
			// Set session username.
			$_SESSION['username'] = $array['username'];
			// Set session first name.
			$_SESSION['first_name'] = $array['first_name'];
			// Set session last name.
			$_SESSION['last_name'] = $array['last_name'];
			// Set session email.
			$_SESSION['email'] = $array['username'];
			// Set session user type.
			$_SESSION['type'] = $array['type'];

			$_SESSION['change_password'] = $array['change_password'];
			
			return true;
			
		}else{return false;}
	}
?>