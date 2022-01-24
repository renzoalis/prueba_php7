<?php
 	header('Content-Type: application/json');
	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_carga = DB_DataObject::factory('carga');

	$do_carga -> whereAdd('carga_id = '.$_POST['id']);
	$do_carga -> find(true);

	$do_carga_detalle = DB_DataObject::factory('carga_detalle');
	$do_carga_detalle -> whereAdd('detalle_carga_id = '.$_POST['id']);
	$do_carga_detalle -> find();

	require_once('public/modales/ver_carga.html');
	exit;
?>