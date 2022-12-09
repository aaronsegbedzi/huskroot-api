<?php
	require($_SERVER['DOCUMENT_ROOT']."/include/v1/class.db.php");

	define('rec_username', secure_input($conn, $_POST['rec_username']));
	define('rec_question', secure_input($conn, $_POST['rec_question']));
	define('rec_answer', secure_input($conn, $_POST['rec_answer']));
	define('rec_check', secure_input($conn, $_POST['rec_check']));

	// Generate random string as temporary password.
	function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'){
	    $str = '';
	    $max = mb_strlen($keyspace, '8bit') - 1;
	    for ($i = 0; $i < $length; ++$i) {
	        $str .= $keyspace[random_int(0, $max)];
	    }
	    return $str;
	}

	$error = true;
	$error = json_encode($error);

	$sql = "SELECT * FROM users WHERE username = '".rec_username."' OR phone = '".rec_username."'";
	$users = mysqli_query($conn, $sql);
	if ($users) {
		if (mysqli_num_rows($users) > 0) {
			foreach ($users as $key => $user) {
				if (rec_check == 'true') {
					if ($user['question'] == str_replace("\\","",rec_question)) {
						if (strtolower($user['answer']) == strtolower(rec_answer)) {
							$random_length = random_int(10,15);
							$temp_password = random_str($random_length);
							$password = password_hash($temp_password, PASSWORD_DEFAULT);
							$sql2 = "UPDATE users SET password = '$password', change_password = 1 WHERE username = '".rec_username."' OR phone = '".rec_username."'";
							$update = mysqli_query($conn, $sql2);
							if ($update) {
								echo $temp_password;
								mysqli_close($conn);
							}else{mysqli_close($conn); echo $error;}
						}else{echo $error;}
					}else{echo $error;}
				}else{echo $error;}
			}
		}else{echo $error;}
	}else{mysqli_close($conn); echo $error;}
?>