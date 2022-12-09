<?php
	require($_SERVER['DOCUMENT_ROOT']."/include/controllers/class.db.php");

	define('reg_email', secure_input($conn, strtolower($_POST['reg_username'])));
	define('reg_phone', secure_input($conn, $_POST['reg_phone']));
	define('reg_password', password_hash(secure_input($conn, $_POST['reg_pass_1']),PASSWORD_DEFAULT));
	define('reg_first_name', secure_input($conn, $_POST['reg_first_name']));
	define('reg_last_name', secure_input($conn, $_POST['reg_last_name']));
	define('reg_question', secure_input($conn, $_POST['reg_question']));
	define('reg_answer', secure_input($conn, strtolower($_POST['reg_answer'])));
	define('reg_type', secure_input($conn, $_POST['reg_type']));

	$sql = "INSERT INTO users(id, username, password, first_name, last_name, phone, type, question, answer, change_password, created_at, updated_at) VALUES (NULL,'".reg_email."','".reg_password."','".reg_first_name."','".reg_last_name."','".reg_phone."','".reg_type."','".reg_question."','".reg_answer."','0',NOW(),NOW())";
	$insert = mysqli_query($conn, $sql);
	if ($insert) {
		echo 'registered';
		mysqli_close($conn);
	}else{return false; mysqli_close($conn);}
?>