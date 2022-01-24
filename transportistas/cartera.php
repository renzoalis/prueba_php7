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

	if($_POST['borrar_transportista']) {
		$transportista = DB_DataObject::factory('transportista');
		$id = $transportista -> eliminarTransportista($_POST);
		header("Location: cartera.php?id_eliminar=".$id);  
	}

	if($_POST['add_transportista']) {
		$transportista = DB_DataObject::factory('transportista');
		$id = $transportista -> nuevoTransportista($_POST);
		header("Location: cartera.php?id_nuevo=".$id);  
	}

	if($_POST['edit_transportista']) {
		$transportista = DB_DataObject::factory('transportista');
		$id = $transportista -> editTransportista($_POST);
		header("Location: cartera.php?id_transportista=".$id); 
	}

	$do_transportistas = DB_DataObject::factory('transportista');
	$transportistas = $do_transportistas -> getTransportistas();
	//print_r($transportistas);exit;

	require_once('public/listado_transportistas.html');
	exit;
?>
