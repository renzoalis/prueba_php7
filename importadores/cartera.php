<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$usr = DB_DataObject::factory('usuario');
	$usr -> usua_id = $_SESSION['usuario']['id'];
	$usr -> find(true);

	$premium = $usr -> esPremium();

	if($_POST['borrar_importador']) {
		$importador = DB_DataObject::factory('importador');
		$id = $importador -> eliminarimportador($_POST);
		header("Location: cartera.php?id_eliminar=".$id);  
	}

	if($_POST['add_importador']) {
		$importador = DB_DataObject::factory('importador');
		$id = $importador -> nuevoimportador($_POST);
		header("Location: cartera.php?id_nuevo=".$id);  
	}

	if($_POST['edit_importador']) {
		$importador = DB_DataObject::factory('importador');
		$id = $importador -> editimportador($_POST);
		header("Location: cartera.php?id_importador=".$id); 
	}

	$do_importadores = DB_DataObject::factory('importador');
	$importadores = $do_importadores -> getimportadores();


	require_once('public/listado_importadores.html');
	exit;
?>
