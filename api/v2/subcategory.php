<?php

	require_once($_SERVER['DOCUMENT_ROOT']."/api/v2/database.php");
	require_once($_SERVER['DOCUMENT_ROOT']."/api/v2/api.php");

	if (isset($_GET['token'])) {
	
		if (CHECK_API_KEY_IS_VALID($conn, $_GET['token'])) {

			if ($_SERVER['REQUEST_METHOD'] == 'GET') {
				
				if (isset($_GET['id'])) {
					
					$id = secure_input($conn, $_GET['id']);

					$sql = "SELECT * FROM `subcategory` WHERE `id` = '$id'";

					$subcategories = mysqli_query($conn, $sql);

					if ($subcategories) {
						
						foreach ($subcategories as $key => $subcategory) {
							header('Content-Type: application/json');
							$response = json_encode($subcategory);
							echo $response;
							http_response_code(200);		
						}

					}else{http_response_code(500);}

				}else if(isset($_GET['name'])) {
					
					$name = secure_input($conn, $_GET['name']);

					$sql = "SELECT * FROM `subcategory` WHERE `name` = '$name'";

					$subcategories = mysqli_query($conn, $sql);

					if ($subcategories) {
						
						foreach ($subcategories as $key => $subcategory) {
							header('Content-Type: application/json');
							$response = json_encode($subcategory);
							echo $response;
							http_response_code(200);	
						}

					}else{http_response_code(500);}

				}else if(isset($_GET['category_id'])) {

					$category_id = secure_input($conn, $_GET['category_id']);

					$sql = "SELECT *, `subcategory`.`id` AS subCatID, (SELECT COUNT(*) FROM `product` JOIN `subsubcategory` ON `product`.`subsubCategoryid` = `subsubcategory`.`id` JOIN `subcategory` ON `subsubcategory`.`subcategory_id` = `subcategory`.`id` WHERE `subcategory`.`id` = subCatID) AS totalProducts FROM `subcategory` WHERE `subcategory`.`category_id` = '$category_id' ORDER BY `id` ASC";

					$subcategories = mysqli_query($conn, $sql);

					if ($subcategories) {
						
						foreach ($subcategories as $key => $subcategory) {
							$object[] = $subcategory;
						}
						
						header('Content-Type: application/json');
						$response = json_encode($object);
						echo $response;
						http_response_code(200);

					}else{http_response_code(500);}

				}else{

					$sql = "SELECT *, `subcategory`.`id` AS subCatID, (SELECT COUNT(*) FROM `product` JOIN `subsubcategory` ON `product`.`subsubCategoryid` = `subsubcategory`.`id` JOIN `subcategory` ON `subsubcategory`.`subcategory_id` = `subcategory`.`id` WHERE `subcategory`.`id` = subCatID) AS totalProducts FROM `subcategory` ORDER BY `id` ASC";

					$subcategories = mysqli_query($conn, $sql);

					foreach ($subcategories as $key => $subcategory) {
						$object[] = $subcategory;
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