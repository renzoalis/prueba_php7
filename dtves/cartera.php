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

	if($_POST['add_dtv']) {
		$do_dtv = DB_DataObject::factory('dtv');
		$id = $do_dtv -> cargarDtv($_POST);
		header("Location: cartera.php");  
	}

	$do_dtves = DB_DataObject::factory('dtv');

	$dtves = $do_dtves -> getDtVsPorEstado(1);

	// colores de la tabla
	$colorrojo = 'background: antiquewhite';

	require_once('public/listado_dtves.html');
	exit;
?>
