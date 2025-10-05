<?php 
include('db_connect.php');
$hashed_cat_id = $_GET['cat_id'];

// Escape the hashed value to prevent SQL injection
$escaped_hashed_cat_id = $conn->real_escape_string($hashed_cat_id);

// Construct the SQL query with the hashed value
$qry = $conn->query("SELECT * FROM categories_list WHERE SHA2(cat_id, 256) = '$escaped_hashed_cat_id'")->fetch_array();


// $qry = $conn->query("SELECT * FROM categories_list where SHA2(cat_id, 256) =".$_GET['cat_id'])->fetch_array();

foreach($qry as $k => $v){
	$$k = $v;
}


include 'categories_list.php';

?>