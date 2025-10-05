<?php
ob_start();
date_default_timezone_set("Asia/Manila");

$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action();
if($action == 'login'){
	$login = $crud->login();
	if($login)
		echo $login;
}
if($action == 'login2'){
	$login = $crud->login2();
	if($login)
		echo $login;
}
if($action == 'logout'){
	$logout = $crud->logout();
	if($logout)
		echo $logout;
}
if($action == 'logout2'){
	$logout = $crud->logout2();
	if($logout)
		echo $logout;
}

if($action == 'signup'){
	$save = $crud->signup();
	if($save)
		echo $save;
}
if($action == 'save_user'){
	$save = $crud->save_user();
	if($save)
		echo $save;
}
if($action == 'update_user'){
	$save = $crud->update_user();
	if($save)
		echo $save;
}
if($action == 'delete_user'){
	$save = $crud->delete_user();
	if($save)
		echo $save;
}

if($action == 'make_default'){
	$save = $crud->make_default();
	if($save)
		echo $save;
}

if($action == 'save_academic'){
	$save = $crud->save_academic();
	if($save)
		echo $save;
}
if($action == 'delete_academic'){
	$save = $crud->delete_academic();
	if($save)
		echo $save;
}


if($action == 'save_faculty'){
	$save = $crud->save_faculty();
	if($save)
		echo $save;
}
if($action == 'delete_faculty'){
	$save = $crud->delete_faculty();
	if($save)
		echo $save;
}

if($action == 'save_category'){
	$save = $crud->save_category();
	if($save)
		echo $save;
}
if($action == 'delete_category'){
	$save = $crud->delete_category();
	if($save)
		echo $save;
}

if($action == 'save_materials'){
	$save = $crud->save_materials();
	if($save)
		echo $save;
}
if($action == 'delete_materials'){
	$save = $crud->delete_materials();
	if($save)
		echo $save;
}

if($action == 'save_employee'){
	$save = $crud->save_employee();
	if($save)
		echo $save;
}
if($action == 'delete_employee'){
	$save = $crud->delete_employee();
	if($save)
		echo $save;
}

if($action == 'save_distribution'){
	$save = $crud->save_distribution();
	if($save)
		echo $save;
}
if($action == 'save_restock'){
	$save = $crud->save_restock();
	if($save)
		echo $save;
}
if($action == 'get_restock_list'){
	$save = $crud->get_restock_list();
	if($save)
		echo $save;
}
if($action == 'fetch_materials'){
	$save = $crud->fetch_materials();
	if($save)
		echo json_encode($data);
}





ob_end_flush();
?>
