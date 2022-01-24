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

	if($_POST['borrar_exportador']) {
		$exportador = DB_DataObject::factory('exportador');
		$id = $exportador -> eliminarExportador($_POST);
		header("Location: cartera.php?id_eliminar=".$id);  
	}

	if($_POST['add_exportador']) {
		$exportador = DB_DataObject::factory('exportador');
		$id = $exportador -> nuevoExportador($_POST);
		header("Location: cartera.php?id_nuevo=".$id);  
	}

	if($_POST['edit_exportador']) {
		$exportador = DB_DataObject::factory('exportador');
		$id = $exportador -> editExportador($_POST);
		header("Location: cartera.php?id_exportador=".$id); 
	}

	$do_exportadores = DB_DataObject::factory('exportador');
	$exportadores = $do_exportadores -> getExportadores();


	require_once('public/listado_exportadores.html');
	exit;
?>
