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
	
	if($_POST['borrar_despachante']) {
		$despachante = DB_DataObject::factory('despachante');
		$id = $despachante -> eliminarDespachante($_POST);
		header("Location: cartera.php?id_eliminar=".$id);  
	}

	if($_POST['add_despachante']) {
		$despachante = DB_DataObject::factory('despachante');
		$id = $despachante -> nuevoDespachante($_POST);
		header("Location: cartera.php?id_nuevo=".$id);  
	}

	if($_POST['edit_despachante']) {
		$despachante = DB_DataObject::factory('despachante');
		$id = $despachante -> editDespachante($_POST);
		header("Location: cartera.php?id_despachante=".$id); 
	}

	$do_despachantes = DB_DataObject::factory('despachante');

	$cc_despachantes = DB_DataObject::factory('despachante_cuenta_corriente');
	
	$despachantes = $do_despachantes -> getDespachantes();
//print_r($do_despachantes);exit;
	require_once('public/listado_despachantes.html');
	exit;
?>
