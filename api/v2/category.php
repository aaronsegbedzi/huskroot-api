<?php

	require_once($_SERVER['DOCUMENT_ROOT']."/api/v2/database.php");
	require_once($_SERVER['DOCUMENT_ROOT']."/api/v2/api.php");

	if (isset($_GET['token'])) {
	
		if (CHECK_API_KEY_IS_VALID($conn, $_GET['token'])) {

			if ($_SERVER['REQUEST_METHOD'] == 'GET') {
				
				if (isset($_GET['id'])) {
					
					$id = secure_input($conn, $_GET['id']);

					$sql = "SELECT * FROM `category` WHERE `id` = '$id'";

					$categories = mysqli_query($conn, $sql);

					if ($categories) {
						
						foreach ($categories as $key => $category) {
							header('Content-Type: application/json');
							$response = json_encode($category);
							echo $response;
							http_response_code(200);		
						}

					}else{http_response_code(500);}

				}else if(isset($_GET['name'])) {
					
					$name = secure_input($conn, $_GET['name']);

					$sql = "SELECT * FROM `category` WHERE `name` = '$name'";

					$categories = mysqli_query($conn, $sql);

					if ($categories) {
						
						foreach ($categories as $key => $category) {
							header('Content-Type: application/json');
							$response = json_encode($category);
							echo $response;
							http_response_code(200);	
						}

					}else{http_response_code(500);}

				}else{

					$sql = "SELECT *, `category`.`id` AS catID, (SELECT COUNT(*) FROM `product` JOIN `subsubcategory` ON `product`.`subsubCategoryid` = `subsubcategory`.`id` JOIN `subcategory` ON `subsubcategory`.`subcategory_id` = `subcategory`.`id` JOIN `category` ON `subcategory`.`category_id` = `category`.`id` WHERE `category`.`id` = catID) AS totalProducts FROM `category` ORDER BY `id` DESC";

					$categories = mysqli_query($conn, $sql);

					foreach ($categories as $key => $category) {
						$object[] = $category;
					}
					
					header('Content-Type: application/json');
					$response = json_encode($object);
					echo $response;
					http_response_code(200);

				}		
			
			}else{http_response_code(405);}

		}else{http_response_code(401);}

	}else{http_response_code(400);}

?>