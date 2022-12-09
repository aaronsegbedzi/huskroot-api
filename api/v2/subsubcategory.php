<?php

	require_once($_SERVER['DOCUMENT_ROOT']."/api/v2/database.php");
	require_once($_SERVER['DOCUMENT_ROOT']."/api/v2/api.php");

	if (isset($_GET['token'])) {
	
		if (CHECK_API_KEY_IS_VALID($conn, $_GET['token'])) {

			if ($_SERVER['REQUEST_METHOD'] == 'GET') {
				
				if (isset($_GET['id'])) {
					
					$id = secure_input($conn, $_GET['id']);

					$sql = "SELECT * FROM `subsubcategory` WHERE `id` = '$id'";

					$subsubcategories = mysqli_query($conn, $sql);

					if ($subsubcategories) {
						
						foreach ($subsubcategories as $key => $subsubcategory) {
							header('Content-Type: application/json');
							$response = json_encode($subsubcategory);
							echo $response;
							http_response_code(200);		
						}

					}else{http_response_code(500);}

				}else if(isset($_GET['name'])) {
					
					$name = secure_input($conn, $_GET['name']);

					$sql = "SELECT * FROM `subsubcategory` WHERE `name` = '$name'";

					$subsubcategories = mysqli_query($conn, $sql);

					if ($subsubcategories) {
						
						foreach ($subsubcategories as $key => $subsubcategory) {
							header('Content-Type: application/json');
							$response = json_encode($subsubcategory);
							echo $response;
							http_response_code(200);	
						}

					}else{http_response_code(500);}

				}else if(isset($_GET['subcategory_id'])) {

					$subcategory_id = secure_input($conn, $_GET['subcategory_id']);

					$sql = "SELECT `subsubcategory`.`id`, `subsubcategory`.`name`, `subsubcategory`.`description`, `subsubcategory`.`created_at`, `subcategory`.`name` AS 'subcategory', `subsubcategory`.`id` AS subSubCatID, (SELECT COUNT(*) FROM `product` JOIN `subsubcategory` ON `product`.`subsubCategoryid` = `subsubcategory`.`id` WHERE `subsubcategory`.`id` = subSubCatID) AS totalProducts FROM `subsubcategory` JOIN `subcategory` ON `subsubcategory`.`subcategory_id` = `subcategory`.`id` WHERE `subcategory_id` = '$subcategory_id' ORDER BY `subcategory_id` ASC";

					$subsubcategories = mysqli_query($conn, $sql);

					if ($subsubcategories) {
						
						foreach ($subsubcategories as $key => $subsubcategory) {
							$object[] = $subsubcategory;
						}
						
						header('Content-Type: application/json');
						$response = json_encode($object);
						echo $response;
						http_response_code(200);	

					}else{http_response_code(500);}

				}else if (isset($_GET['category_id'])) {

					$category_id = secure_input($conn, $_GET['category_id']);

					$sql = "SELECT `subsubcategory`.`id`, `subsubcategory`.`name`, `subsubcategory`.`description`, `subsubcategory`.`created_at`, `subcategory`.`name` AS 'subcategory' FROM `subsubcategory` JOIN `subcategory` ON `subsubcategory`.`subcategory_id` = `subcategory`.`id` JOIN `category` ON `subcategory`.`category_id` = `category`.`id` WHERE `category`.`id` = '$category_id' ORDER BY `subcategory_id` ASC";

					$subsubcategories = mysqli_query($conn, $sql);

					if ($subsubcategories) {
						
						foreach ($subsubcategories as $key => $subsubcategory) {
							$object[] = $subsubcategory;
						}
						
						header('Content-Type: application/json');
						$response = json_encode($object);
						echo $response;
						http_response_code(200);
					}else{http_response_code(500);}	

				}else{

					$sql = "SELECT *, `subsubcategory`.`id` AS subSubCatID, (SELECT COUNT(*) FROM `product` JOIN `subsubcategory` ON `product`.`subsubCategoryid` = `subsubcategory`.`id` WHERE `subsubcategory`.`id` = subSubCatID) AS totalProducts FROM `subsubcategory` ORDER BY `id` ASC";

					$subsubcategories = mysqli_query($conn, $sql);

					foreach ($subsubcategories as $key => $subsubcategory) {
						$object[] = $subsubcategory;
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