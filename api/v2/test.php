<?php
	function dateDifference($date_1 , $date_2 , $differenceFormat = '%h' ){
	    $datetime1 = date_create($date_1);
	    $datetime2 = date_create($date_2);
	    
	    $interval = date_diff($datetime1, $datetime2);
	    
	    return $interval->format($differenceFormat);
	    
	}
	$now = date('Y-m-d h:i:s A', time());
	// echo password_hash('12345678', PASSWORD_DEFAULT);
	echo dateDifference($now, '2017-11-13 13:20:27');
	// echo $now;

?>