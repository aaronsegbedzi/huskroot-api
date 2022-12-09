<?php
	require_once($_SERVER['DOCUMENT_ROOT']."/api/v2/database.php");
	require_once($_SERVER['DOCUMENT_ROOT']."/api/v2/api.php");
	
	if ($_SERVER['REQUEST_METHOD'] == 'GET'){
		
		if (isset($_GET['token'])) {
			
			if (CHECK_API_KEY_IS_VALID($conn, $_GET['token'])) {
				
				http_response_code(202);
			
			}else{http_response_code(401);}

		}else{http_response_code(400);}

	}else if ($_SERVER['REQUEST_METHOD'] == 'POST') {		

		if (isset($_POST['username']) && isset($_POST['password'])) {		

			$username = secure_input($conn, $_POST['username']);
			$password = secure_input($conn, $_POST['password']);
			
			$username = format_input($username,'lower');

			$sql = "SELECT * FROM `user` WHERE `user`.`username` = '$username' OR `user`.`id` = (SELECT `people`.`userid` FROM `people` WHERE `people`.`phone` = '$username')";
			
			$users = mysqli_query($conn, $sql) or exit(http_response_code(500));
			
				if (mysqli_num_rows($users) > 0) {
					
					foreach ($users as $key => $user) {
						
						if (password_verify($password, $user['password'])) {

							$sql = "SELECT * FROM `people` WHERE `userid` = '".$user['id']."'";

							$people = mysqli_query($conn, $sql) or exit(http_response_code(500));
								
							if (mysqli_num_rows($people) > 0) {

								$token = GENERATE_API_KEY(50);

								$sql = "INSERT INTO `token`(`id`, `key`, `created_at`, `userid`) VALUES (NULL,'$token',NOW(),'".$user['id']."')";
								
								$update = mysqli_query($conn, $sql) or exit(http_response_code(500));
								
								foreach ($people as $key => $person) {

									$array = array(
										'id' => $user['id'],
										'first_name' => $person['first_name'],
										'last_name' => $person['last_name'],
										'person_id' => $person['id'],
										'username' => $user['username'],
										'email' => $person['email'],
										'type' => $user['type'],
										'phone' => $person['phone'],
										'token' => $token,
										'expire' => date("Y-m-d h:i:s", time())
									);	
								}

								header('Content-Type: application/json');
								echo json_encode($array);
								http_response_code(200);

							}else{http_response_code(401);}

						}else{http_response_code(401);}}

				}else{http_response_code(401);}

		}else{http_response_code(417);}

	}else if($_SERVER['REQUEST_METHOD'] == 'DELETE'){
		
		if (isset($_GET['token'])) {

			if (CHECK_API_KEY_IS_VALID($conn, $_GET['token'])) {
				
				if (DELETE_TOKEN($conn, $_GET['token'])) {
					
					http_response_code(200);
				
				}else{http_response_code(500);}

			}else{http_response_code(404);}

		}else{http_response_code(417);}

	}else if($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
		
		http_response_code(200);
		
	}else{http_response_code(405);}	
?>