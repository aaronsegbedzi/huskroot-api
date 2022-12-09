<?php

	require_once($_SERVER['DOCUMENT_ROOT']."/api/v2/database.php");
	require_once($_SERVER['DOCUMENT_ROOT']."/api/v2/api.php");

	if (isset($_GET['token'])) {
	
		if (CHECK_API_KEY_IS_VALID($conn, $_GET['token'])) {

			if ($_SERVER['REQUEST_METHOD'] == 'GET') {

				if (isset($_GET['action']) && $_GET['action'] == 'report') {
					
					$sql = "SELECT * FROM `report` WHERE `id` = (SELECT `reportid` FROM `people` JOIN `user` ON `people`.`userid` = `user`.`id` JOIN `token` ON `token`.`userid` = `user`.`id` WHERE `token`.`key` = '".$_GET['token']."')";

					$reports = mysqli_query($conn, $sql) or exit(http_response_code(500));

					if (mysqli_num_rows($reports) > 0) {
						
						foreach ($reports as $key => $report) {
							
							header('Content-Type: application/json');
							$response = json_encode($report);
							echo $response;	

						}

					}else{http_response_code(404);}

				}else if(isset($_GET['action']) && $_GET['action'] == 'product') {

					$sql = "SELECT COUNT(*) AS 'total' FROM `product` JOIN `people` ON `people`.`id` = `product`.`peopleid` JOIN `token` ON `token`.`userid` = `people`.`userid` WHERE `token`.`key` = '".$_GET['token']."'";

					$products = mysqli_query($conn, $sql) or exit(http_response_code(500));

					if (mysqli_num_rows($products)) {
						
						foreach ($products as $key => $product) {
							
							header('Content-Type: application/json');
							$response = json_encode($product);
							echo $response;

						}

					}else{http_response_code(404);}

				}else{http_response_code(400);}
				
			}else{http_response_code(405);}

		}else{http_response_code(401);}

	}else{http_response_code(400);}

?>					