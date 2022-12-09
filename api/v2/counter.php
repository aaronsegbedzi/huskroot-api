<?php
function addView($token, $conn){
	$total = 0;
	$report_id = 0;
	$sql = "SELECT `views`, `report`.`id` FROM `report` JOIN `people` ON `report`.`id` = `people`.`reportid` WHERE `people`.`id` = (SELECT `people`.`id` FROM `token` JOIN `people` ON `token`.`userid` = `people`.`userid` WHERE `token`.`key` = '$token')";
	$select = mysqli_query($conn, $sql);
	foreach ($select as $key => $value) {
		$total = $value['views'] + 1;
		$report_id = $value['id'];
	}
	$sql = "UPDATE `report` SET `views` = '$total' WHERE `id` = '$report_id'";
	$update = mysqli_query($conn, $sql);
}
?>