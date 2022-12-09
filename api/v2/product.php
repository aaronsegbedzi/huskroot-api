<?php

	require_once($_SERVER['DOCUMENT_ROOT']."/api/v2/database.php");
	require_once($_SERVER['DOCUMENT_ROOT']."/api/v2/api.php");
	require_once($_SERVER['DOCUMENT_ROOT']."/api/v2/counter.php");

	if (isset($_GET['token'])) {
	
		if (CHECK_API_KEY_IS_VALID($conn, $_GET['token'])) {

			if ($_SERVER['REQUEST_METHOD'] == 'GET') {
				
				if (isset($_GET['id'])) {
					
					$id = secure_input($conn, $_GET['id']);

					$sql = "SELECT `product`.*, `people`.`first_name`, `people`.`last_name`, `people`.`phone`, `people`.`email`, `subsubcategory`.`name` AS 'subsubcategory' FROM `product` JOIN `people` ON `product`.`peopleid` = `people`.`id` JOIN `subsubcategory` ON `product`.`subsubcategoryid` = `subsubcategory`.`id` WHERE `product`.`id` = '$id' ORDER BY `created_at` DESC";

					$products = mysqli_query($conn, $sql) or exit(http_response_code(500));

					if (mysqli_num_rows($products) > 0) {

						addView($_GET['token'], $conn);

						foreach ($products as $key => $product) {
							
							header('Content-Type: application/json');
							$response = json_encode($product);
							echo $response;		
						
						}

					}else{http_response_code(404);}

				}else if(isset($_GET['name'])) {
					
					$name = secure_input($conn, $_GET['name']);

					$sql = "SELECT * FROM `product` WHERE `name` = '$name'";

					$products = mysqli_query($conn, $sql) or exit(http_response_code(500));

					if (mysqli_num_rows($products) > 0) {
						
						foreach ($products as $key => $product) {

							header('Content-Type: application/json');
							$response = json_encode($product);
							echo $response;	
						
						}

					}else{http_response_code(404);}

				}else if(isset($_GET['subsubcategory_id'])) {

					$subsubcategory_id = secure_input($conn, $_GET['subsubcategory_id']);

					$sql = "SELECT `product`.*, `people`.`first_name`, `people`.`last_name`, `subsubcategory`.`name` AS 'subsubcategory'  FROM `product` JOIN `people` ON `product`.`peopleid` = `people`.`id` JOIN `subsubcategory` ON `product`.`subsubcategoryid` = `subsubcategory`.`id` WHERE `subsubcategory`.`id` = '$subsubcategory_id' ORDER BY `created_at` DESC";

					$products = mysqli_query($conn, $sql) or exit(http_response_code(500));

					if (mysqli_num_rows($products)) {
						
						foreach ($products as $key => $product) {
							$object[] = $product;
						}
						
						header('Content-Type: application/json');
						$response = json_encode($object);
						echo $response;	

					}else{http_response_code(404);}

				}else if(isset($_GET['farmer_id'])) {

					$farmer_id = secure_input($conn, $_GET['farmer_id']);

					$sql = "SELECT `product`.*, `people`.`first_name`, `people`.`last_name`, `people`.`phone`, `people`.`email`, `subsubcategory`.`name` AS 'subsubcategory'  FROM `product` JOIN `people` ON `product`.`peopleid` = `people`.`id` JOIN `subsubcategory` ON `product`.`subsubcategoryid` = `subsubcategory`.`id` WHERE `product`.`peopleid` = '$farmer_id' ORDER BY `created_at` DESC";

					$products = mysqli_query($conn, $sql) or exit(http_response_code(500));

					if (mysqli_num_rows($products)) {
						
						foreach ($products as $key => $product) {
							$object[] = $product;
						}
						
						header('Content-Type: application/json');
						$response = json_encode($object);
						echo $response;	

					}else{http_response_code(404);}

				}else{

					$sql = "SELECT `product`.*, `people`.`first_name`, `people`.`last_name` FROM `product` JOIN `people` ON `product`.`peopleid` = `people`.`id` JOIN `subsubcategory` ON `product`.`subsubCategoryid` = `subsubcategory`.`id`";

					$products = mysqli_query($conn, $sql);

					foreach ($products as $key => $product) {
						$object[] = $product;
					}
					
					header('Content-Type: application/json');
					$response = json_encode($object);
					echo $response;	

				}		
			
			}else if($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_GET['update'])) {
				
				$key = $_GET['token'];

				$sql = "SELECT `token`.`userid`, `type`, `people`.`id` FROM `token` JOIN `user` ON `token`.`userid` = `user`.`id` JOIN `people` ON `people`.`userid` = `token`.`userid` WHERE `key` = '$key'";
				
				$tokens = mysqli_query($conn, $sql) or exit(http_response_code(500));

				if (mysqli_num_rows($tokens) > 0) {
						
						foreach ($tokens as $key => $token) {
							
							if ($token['type'] == 1) {

								$farmer_id = $token['id'];								
								$name = secure_input($conn, $_POST['name']);
								$price = secure_input($conn, $_POST['price']);
								$currency = secure_input($conn, $_POST['currency']);
								$unit = secure_input($conn, $_POST['unit']);
								$description = secure_input($conn, $_POST['description']);
								$location = secure_input($conn, $_POST['location']);
								$lat = secure_input($conn, $_POST['lat']);
								$lng = secure_input($conn, $_POST['lng']);
								$subsubcategory_id = secure_input($conn, $_POST['subsubcategory_id']);

								$sql = "INSERT INTO `product`(`id`, `name`, `price`, `currency`, `unit`, `description`, `location`, `lat`, `lng`, `created_at`, `updated_at`, `subsubCategoryid`, `peopleid`) VALUES (NULL,'$name','$price','$currency','$unit','$description','$location','$lat','$lng',NOW(),NOW(),'$subsubcategory_id','$farmer_id')";
								
								$insert = mysqli_query($conn, $sql) or exit(http_response_code(500));

								$inserted_id = mysqli_insert_id($conn);

								if (is_uploaded_file($_FILES['audio']['tmp_name'])) {

									$target_dir = $_SERVER['DOCUMENT_ROOT']."/assets/audio/products/".$token['id'];
									if (!file_exists($target_dir)) {
										mkdir($_SERVER['DOCUMENT_ROOT']."/assets/audio/products/".$token['id']);
									}

									// if ($_FILES["audio"]["size"] > 10485760) {
									//     exit(http_response_code(413));
									// }

									$allowed_file_types = array('3gp', '3GP', 'wav', 'WAV');
									$FileType = pathinfo($_FILES['audio']['name'],PATHINFO_EXTENSION);
									if(!in_array($FileType, $allowed_file_types)) {
										exit(http_response_code(415));
									}

									$target_file = $target_dir.'/'.$inserted_id.'.wav';
									move_uploaded_file($_FILES["audio"]["tmp_name"], $target_file) or exit(http_response_code(500));

								}

								if (is_uploaded_file($_FILES['video']['tmp_name'])) {

									$target_dir = $_SERVER['DOCUMENT_ROOT']."/assets/video/products/".$token['id'];
									if (!file_exists($target_dir)) {
										mkdir($_SERVER['DOCUMENT_ROOT']."/assets/video/products/".$token['id']);
									}

									// if ($_FILES["video"]["size"] > 10485760) {
									//     exit(http_response_code(413));
									// }

									$allowed_file_types = array('mp4', 'MP4');
									$FileType = pathinfo($_FILES['video']['name'],PATHINFO_EXTENSION);
									if(!in_array($FileType, $allowed_file_types)) {
										exit(http_response_code(415));
									}

									$target_file = $target_dir.'/'.$inserted_id.'.'.$FileType;
									move_uploaded_file($_FILES["video"]["tmp_name"], $target_file) or exit(http_response_code(500));
									
								}

								if (is_uploaded_file($_FILES['img']['tmp_name'])) {

									$target_dir = $_SERVER['DOCUMENT_ROOT']."/assets/img/products/".$token['id'];
									if (!file_exists($target_dir)) {
										mkdir($_SERVER['DOCUMENT_ROOT']."/assets/img/products/".$token['id']);
									}

									// if ($_FILES["img"]["size"] > 500000) {
									//     exit(http_response_code(413));
									// }

									$allowed_file_types = array('jpg', 'JPG');
									$FileType = pathinfo($_FILES['img']['name'],PATHINFO_EXTENSION);
									if(!in_array($FileType, $allowed_file_types)) {
										exit(http_response_code(415));
									}
									
									$target_file = $target_dir.'/'.$inserted_id.'.'.$FileType;
									move_uploaded_file($_FILES["img"]["tmp_name"], $target_file) or exit(http_response_code(500));
									
								}

								$response = array('inserted_id' => $inserted_id);
								header('Content-Type: application/json');
								echo json_encode($response);

								http_response_code(201);

							}else{http_response_code(401);}

						}

				}else{http_response_code(404);}
			
			}else if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['update'])){			
				
				$key = $_GET['token'];

				$sql = "SELECT `token`.`userid`, `type`, `people`.`id` FROM `token` JOIN `user` ON `token`.`userid` = `user`.`id` JOIN `people` ON `people`.`userid` = `token`.`userid` WHERE `key` = '$key'";
				
				$tokens = mysqli_query($conn, $sql) or exit(http_response_code(500));

				if (mysqli_num_rows($tokens) > 0) {
						
					foreach ($tokens as $key => $token) {
						
						if ($token['type'] == 1) {

							if (isset($_GET['id'])) {
								
								$id = secure_input($conn, $_GET['id']);
								$name = secure_input($conn, $_POST['name']);
								$price = secure_input($conn, $_POST['price']);
								$currency = secure_input($conn, $_POST['currency']);
								$unit = secure_input($conn, $_POST['unit']);
								$description = secure_input($conn, $_POST['description']);
								$location = secure_input($conn, $_POST['location']);
								$lat = secure_input($conn, $_POST['lat']);
								$lng = secure_input($conn, $_POST['lng']);
								$subsubcategory_id = secure_input($conn, $_POST['subsubcategory_id']);

								$sql = "UPDATE `product` SET `name`='$name',`price`='$price',`currency`='$currency',`unit`='$unit',`description`='$description',`location`='$location',`lat`='$lat',`lng`='$lng',`updated_at`=NOW(),`subsubCategoryid`='$subsubcategory_id' WHERE `id` = '$id'";
								
								$update = mysqli_query($conn, $sql) or exit(mysqli_error($conn));

								if (is_uploaded_file($_FILES['audio']['tmp_name'])) {

									$target_dir = $_SERVER['DOCUMENT_ROOT']."/assets/audio/products/".$token['id'];
									if (!file_exists($target_dir)) {
										mkdir($_SERVER['DOCUMENT_ROOT']."/assets/audio/products/".$token['id']);
									}

									// if ($_FILES["audio"]["size"] > 5000000) {
									//     exit(http_response_code(413));
									// }

									$allowed_file_types = array('3gp', '3GP', 'wav', 'WAV');
									$FileType = pathinfo($_FILES['audio']['name'],PATHINFO_EXTENSION);
									if(!in_array($FileType, $allowed_file_types)) {
										exit(http_response_code(415));
									}

									$target_file = $target_dir.'/'.$id.'.wav';
									
									if (file_exists($target_file)) {
										unlink($target_file);
									}

									move_uploaded_file($_FILES["audio"]["tmp_name"], $target_file) or exit(http_response_code(500));

								}

								if (is_uploaded_file($_FILES['video']['tmp_name'])) {

									$target_dir = $_SERVER['DOCUMENT_ROOT']."/assets/video/products/".$token['id'];
									if (!file_exists($target_dir)) {
										mkdir($_SERVER['DOCUMENT_ROOT']."/assets/video/products/".$token['id']);
									}

									// if ($_FILES["video"]["size"] > 5000000) {
									//     exit(http_response_code(413));
									// }

									$allowed_file_types = array('mp4', 'MP4');
									$FileType = pathinfo($_FILES['video']['name'],PATHINFO_EXTENSION);
									if(!in_array($FileType, $allowed_file_types)) {
										exit(http_response_code(415));
									}

									$target_file = $target_dir.'/'.$id.'.'.$FileType;

									if (file_exists($target_file)) {
										unlink($target_file);
									}

									move_uploaded_file($_FILES["video"]["tmp_name"], $target_file) or exit(http_response_code(500));
									
								}

								if (is_uploaded_file($_FILES['img']['tmp_name'])) {

									$target_dir = $_SERVER['DOCUMENT_ROOT']."/assets/img/products/".$token['id'];
									if (!file_exists($target_dir)) {
										mkdir($_SERVER['DOCUMENT_ROOT']."/assets/img/products/".$token['id']);
									}

									// if ($_FILES["img"]["size"] > 500000) {
									//     exit(http_response_code(413));
									// }

									$allowed_file_types = array('jpg', 'JPG');
									$FileType = pathinfo($_FILES['img']['name'],PATHINFO_EXTENSION);
									if(!in_array($FileType, $allowed_file_types)) {
										exit(http_response_code(415));
									}
									
									$target_file = $target_dir.'/'.$id.'.'.$FileType;

									if (file_exists($target_file)) {
										unlink($target_file);
									}

									move_uploaded_file($_FILES["img"]["tmp_name"], $target_file) or exit(http_response_code(500));
									
								}

								http_response_code(202);

							}else{http_response_code(400);}

						}else{http_response_code(401);}
					}
				}else{http_response_code(404);}

			}else if($_SERVER['REQUEST_METHOD'] == 'DELETE') {
				
				if (isset($_GET['id']) && isset($_GET['farmer_id'])) {
					
					$sql = "DELETE FROM `product` WHERE `id` = '".$_GET['id']."'";
					
					$delete = mysqli_query($conn, $sql);

					if ($delete) {

						$target_dir = $_SERVER['DOCUMENT_ROOT']."/assets/audio/products/".$_GET['farmer_id'];
						if (file_exists($target_dir.'/'.$_GET['id'].'.wav')) {
							unlink($target_dir.'/'.$_GET['id'].'.wav');
						}
						

						$target_dir = $_SERVER['DOCUMENT_ROOT']."/assets/video/products/".$_GET['farmer_id'];
						if (file_exists($target_dir.'/'.$_GET['id'].'.mp4')) {
							unlink($target_dir.'/'.$_GET['id'].'.mp4');
						}
						

						$target_dir = $_SERVER['DOCUMENT_ROOT']."/assets/img/products/".$_GET['farmer_id'];
						if (file_exists($target_dir.'/'.$_GET['id'].'.jpg')) {
							unlink($target_dir.'/'.$_GET['id'].'.jpg');
						}
						
						http_response_code(200);

					}else{http_response_code(500);}

				}else{http_response_code(400);}
			}else if($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
				http_response_code(200);
			}else{http_response_code(405);}

		}else{http_response_code(401);}

	}else{http_response_code(400);}

?>