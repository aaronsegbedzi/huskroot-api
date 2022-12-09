<?php
	function GENERATE_API_KEY($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'){
	    $str = '';
	    $max = mb_strlen($keyspace, '8bit') - 1;
	    for ($i = 0; $i < $length; ++$i) {
	        $str .= $keyspace[random_int(0, $max)];
	    }
	    return $str;
	}

	function dateDifference($date_1 , $date_2 , $differenceFormat = '%h' ){
	    $datetime1 = date_create($date_1);
	    $datetime2 = date_create($date_2);	    
	    $interval = date_diff($datetime1, $datetime2);   
	    return $interval->format($differenceFormat);
	}

	function CHECK_API_KEY_IS_VALID($conn, $token){
		$now = date('Y-m-d h:i:s A', time());
		$sql = "SELECT `created_at`, `key` FROM `token` WHERE `key` = '$token'";
		$tokens = mysqli_query($conn, $sql) or exit(http_response_code(500));
		if (mysqli_num_rows($tokens) > 0) {		
			foreach ($tokens as $key => $token) {
				if (dateDifference($token['created_at'], $now) < 12) {
					return true;
				}else{ 
					DELETE_TOKEN($conn, $token['key']) or exit(http_response_code(500));
					return false; 
				}
			}
		}else{return false;}
	}

	function DELETE_TOKEN($conn, $token){
		$sql = "DELETE FROM `token` WHERE `key` = '$token'";
		$delete = mysqli_query($conn, $sql);
		if ($delete) {
			return true;
		}else{ return false; }
	}
?>