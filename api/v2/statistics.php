<?php
	require_once($_SERVER['DOCUMENT_ROOT']."/api/v2/database.php");
	require_once($_SERVER['DOCUMENT_ROOT']."/api/v2/api.php");

	if (isset($_GET['token'])) {
	
		if (CHECK_API_KEY_IS_VALID($conn, $_GET['token'])) {

			if ($_SERVER['REQUEST_METHOD'] == 'GET') {

				$sql = "SELECT COUNT(*) AS totalProducts, (SELECT `people`.`id` FROM `token` JOIN `people` ON `token`.`userid` = `people`.`userid` WHERE `token`.`key` = '".$_GET['token']."') AS Query, (SELECT COUNT(*) FROM `category`) AS totalCategory, (SELECT COUNT(*) FROM `subcategory`) AS totalSubCategory, (SELECT COUNT(*) FROM `subsubcategory`) AS totalSubSubCategory, (SELECT COUNT(*) FROM `product` WHERE `peopleid` = Query) AS totalUploadedProducts, (SELECT `views` FROM `report` JOIN `people` ON `report`.`id` = `people`.`reportid` WHERE `people`.`id` = Query ) AS totalViews, (SELECT `clicks` FROM `report` JOIN `people` ON `report`.`id` = `people`.`reportid` WHERE `people`.`id` = Query ) AS totalClicks, (SELECT `refer` FROM `report` JOIN `people` ON `report`.`id` = `people`.`reportid` WHERE `people`.`id` = Query ) AS totalRefers FROM `product`";

				$count = mysqli_query($conn, $sql) or exit(http_response_code(500));

				if (mysqli_num_rows($count) > 0) {
					foreach ($count as $key => $value) {
						header('Content-Type: application/json');
						$response = json_encode($value);
						echo $response;
					}

				}else{http_response_code(404);}

			}else{http_response_code(400);}

		}else{http_response_code(401);}

	}else{http_response_code(400);}
?>