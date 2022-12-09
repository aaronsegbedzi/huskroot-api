<?php
	session_start();
	require($_SERVER['DOCUMENT_ROOT']."/include/controllers/class.db.php");

	$login_password = secure_input($conn, $_POST['login_password']);
	$password = secure_input($conn, $_POST['password']);

	$sql = "SELECT * FROM users WHERE username ='".$_SESSION['username']."'";
	$users = mysqli_query($conn, $sql);
	if ($users) {
		if (mysqli_num_rows($users) > 0) {
			foreach ($users as $key => $user) {
				if (password_verify($login_password, $user['password'])) {
					$password = password_hash($password, PASSWORD_DEFAULT);
					$sql = "UPDATE users SET password = '".$password."', change_password = 0 WHERE username = '".$_SESSION['username']."'";
					$update = mysqli_query($conn, $sql);
					if ($update) {
						$_SESSION['change_password'] = 0;
						echo 'password_changed';
					}else{mysqli_close($conn); return false;}
				}else{return false;}
			}
		}else{mysqli_close($conn); return false;}
	}else{mysqli_close($conn); return false;}
	
?>