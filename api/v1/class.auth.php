<?php
	// Require the database connection script. 
	require_once($_SERVER['DOCUMENT_ROOT']."/include/v1/class.db.php");
	require_once($_SERVER['DOCUMENT_ROOT']."/include/v1/class.api_key.php");

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		
		// If request method is POST, check if the POST values defined below have been set.
		if (isset($_POST['login_username']) && isset($_POST['login_password'])) {
			
			// Get values from login form.
			$login_username = secure_input($conn, $_POST['login_username']);
			$login_password = secure_input($conn, $_POST['login_password']);
			
			// Format values from login form.
			$login_username = format_input($login_username,'lower');

			// SQL Query
			$sql = "SELECT username, password, first_name, last_name, phone, type, change_password FROM users WHERE username = '".$login_username."' OR phone = '".$login_username."'";

			// Get the results from the database.
			$users = mysqli_query($conn, $sql);

			if ($users) {
				// Check if number of results is greater than zero.
				if (mysqli_num_rows($users) > 0) {
					foreach ($users as $key => $user) {
						
						// Check if $login_password value is equal to hashed password value for the user.
						if (password_verify($login_password, $user['password'])) {

							// Start session.
							session_start();

							// Set session username.
							$_SESSION['username'] = $user['username'];

							// Set session first name.
							$_SESSION['first_name'] = $user['first_name'];

							// Set session last name.
							$_SESSION['last_name'] = $user['last_name'];

							// Set session email.
							$_SESSION['email'] = $user['username'];

							// Set session user type.
							$_SESSION['type'] = $user['type'];

							// Set session change_password status.
							$_SESSION['change_password'] = $user['change_password'];

							$status = false;
							
							$message = REPLY_LOGIN_SUCCESS;

						}else{ $status = true; $message = ERROR_INVALID_PASSWORD; }
					}
				}else{$status = true; $message = ERROR_INVALID_USERNAME; }

			}else{ $status = true; $message = ERROR_DEFAULT; }

		}else{ $status = true; $message = ERROR_LOGIN_VALUES; }

	}else{ $status = true; $message = ERROR_REQUEST_METHOD; }

	// JSON response.
	$response = array('error' => $status, 'message' => $message);
	header('Content-Type: application/json');
	echo json_encode($response);
?>