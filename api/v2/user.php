<?php

	require_once($_SERVER['DOCUMENT_ROOT']."/api/v2/database.php");
	require_once($_SERVER['DOCUMENT_ROOT']."/api/v2/api.php");

	if (isset($_GET['token'])) {
	
		if (CHECK_API_KEY_IS_VALID($conn, $_GET['token'])) {

			if ($_SERVER['REQUEST_METHOD'] == 'GET') {
				
				$sql = "SELECT * FROM `user` JOIN `token` ON `user`.`id` = `token`.`userid` JOIN `people` ON `people`.`userid` = `user`.`id` WHERE `token`.`key` = '".$_GET['token']."'";			

				$people = mysqli_query($conn, $sql) or exit(http_response_code(500));;

				if (mysqli_num_rows($people) > 0) {
					
					foreach ($people as $key => $person) {
						
						header('Content-Type: application/json');
						$response = json_encode($person);
						echo $response;	

					}

				}else{http_response_code(404);}
			
			}else if($_SERVER['REQUEST_METHOD'] == 'POST') {

				if (isset($_GET['action']) && $_GET['action'] == 'password') {

					$password = secure_input($conn, $_POST['password']);

					$password = password_hash($password, PASSWORD_DEFAULT);

					$sql = "UPDATE `user` SET `password` = '$password', `updated_at` = NOW() WHERE `user`.`id` = (SELECT `userid` FROM `token` WHERE `key` = '".$_GET['token']."')";

					$update = mysqli_query($conn, $sql) or exit(http_response_code(500));

					http_response_code(200);
					
				}else{

					$first_name = secure_input($conn, $_POST['first_name']);
					$last_name = secure_input($conn, $_POST['last_name']);
					$email = secure_input($conn, $_POST['email']);
					$phone = secure_input($conn, $_POST['phone']);

					$sql = "UPDATE `people` SET `first_name`='$first_name',`last_name`='$last_name',`phone`='$phone',`email`='$email',`updated_at`=NOW() WHERE `people`.`userid` = (SELECT `user`.`id` FROM `user` JOIN `token` ON `user`.`id` = `token`.`userid` WHERE `token`.`key` = '".$_GET['token']."')";

					$update = mysqli_query($conn, $sql) or exit(http_response_code(500));

					http_response_code(200);
				}

			}else if($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

				http_response_code(200);

			}else{http_response_code(405);}

		}else{http_response_code(401);}

	}else if(isset($_GET['action']) && $_GET['action'] == 'register') {

		$first_name = secure_input($conn, $_POST['reg_first_name']);
		$last_name = secure_input($conn, $_POST['reg_last_name']);
		$email = secure_input($conn, $_POST['reg_username']);
		$phone = secure_input($conn, $_POST['reg_phone']);
		$type = secure_input($conn, $_POST['reg_type']);

		$username = secure_input($conn, $_POST['reg_username']);
		$password = secure_input($conn, $_POST['reg_password']);
		$password = password_hash($password, PASSWORD_DEFAULT);

		$security_question = secure_input($conn, $_POST['reg_question']);
		$security_answer = secure_input($conn, $_POST['reg_answer']);

		$sql = "INSERT INTO `user`(`id`, `username`, `password`, `security_question`, `security_answer`, `type`, `created_at`, `updated_at`) VALUES (NULL,'$username','$password','$security_question','$security_answer','$type',NOW(),NOW())";

		$insert = mysqli_query($conn, $sql) or exit(http_response_code(500));

		$user_inserted_id = mysqli_insert_id($conn);

		$sql = "INSERT INTO `report`(`id`, `views`, `clicks`, `refer`, `created_at`, `updated_at`) VALUES (NULL,'0','0','0',NOW(),NOW())";

		$insert = mysqli_query($conn, $sql) or exit(http_response_code(500));

		$report_inserted_id = mysqli_insert_id($conn);

		$sql = "INSERT INTO `people`(`id`, `first_name`, `last_name`, `phone`, `email`, `created_at`, `updated_at`, `userid`, `reportid`) VALUES (NULL,'$first_name','$last_name','$phone','$email',NOW(),NOW(),'$user_inserted_id','$report_inserted_id')";

		$insert = mysqli_query($conn, $sql) or exit(http_response_code(500));

		http_response_code(201);

	}else if(isset($_GET['action']) && $_GET['action'] == 'forgot_password'){

		function random_str($length, $keyspace = '0123456789'){
		    $str = '';
		    $max = mb_strlen($keyspace, '8bit') - 1;
		    for ($i = 0; $i < $length; ++$i) {
		        $str .= $keyspace[random_int(0, $max)];
		    }
		    return $str;
		}	
		
		$username = secure_input($conn, $_POST['rec_username']);
		$security_question = secure_input($conn, $_POST['rec_question']);
		$security_answer = secure_input($conn, $_POST['rec_answer']);

		$sql = "SELECT * FROM `user` WHERE `username` = '$username' OR `user`.`id` = (SELECT `people`.`userid` FROM `people` WHERE `people`.`phone` = '$username')";
		
		$users = mysqli_query($conn, $sql) or exit(http_response_code(500));

		if (mysqli_num_rows($users) > 0) {
			
			foreach ($users as $key => $user) {
				
				if ($user['security_question'] == str_replace("\\", "", $security_question)) {
					
					if ($user['security_answer'] == $security_answer) {

						$password_unhashed = random_str(4);
						$password = password_hash($password_unhashed, PASSWORD_DEFAULT);
						
						$sql = "UPDATE `user` SET `password` = '$password' WHERE `user`.`username` = '$username' OR `user`.`id` = (SELECT `people`.`userid` FROM `people` WHERE `people`.`phone` = '$username')";

						$update = mysqli_query($conn, $sql) or exit(http_response_code(500));
						
						echo $password_unhashed;
						http_response_code(200);

					}else{http_response_code(401);}

				}else{http_response_code(401);}

			}
			
		}else{http_response_code(404);}	
	
	}else{http_response_code(400);}

?>					