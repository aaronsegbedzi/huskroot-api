<?php
	function getCategories($conn){
		$sql = "SELECT * FROM categories ORDER BY name ASC";
		$categories = mysqli_query($conn, $sql);
		if ($categories) {
			if (mysqli_num_rows($categories) > 0) {
				return $categories;
			}else{return false;}
		}else{return false;}
	}

	function getSubCategories($conn, $category_id){
		$sql = "SELECT * FROM sub_categories WHERE category_id = '$category_id' ORDER BY name ASC";
		$sub_categories = mysqli_query($conn, $sql);
		if ($sub_categories) {
			if (mysqli_num_rows($sub_categories) > 0) {
				return $sub_categories;
			}else{return false;}
		}else{return false;}
	}

	function getCategory($conn, $id){
		$sql = "SELECT * FROM categories WHERE id = '$id'";
		$category = mysqli_query($conn, $sql);
		if ($category) {
			if (mysqli_num_rows($category) > 0) {
				foreach ($category as $key => $value) {
					return $value;
				}
			}else{return false;}
		}else{return false;}
	}

	function getSubCategory($conn, $id){
		$sql = "SELECT * FROM sub_categories WHERE id = '$id'";
		$sub_category = mysqli_query($conn, $sql);
		if ($sub_category) {
			if (mysqli_num_rows($sub_category) > 0) {
				foreach ($sub_category as $key => $value) {
					return $value;
				}
			}else{return false;}
		}else{return false;}
	}

?>