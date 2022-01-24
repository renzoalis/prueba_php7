<?php
 	header('Content-Type: application/json');
	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_descarga = DB_DataObject::factory('descarga');

	$do_descarga -> whereAdd('desc_id = '.$_POST['id']);
	$do_descarga -> find(true);

	$do_descarga_detalle = DB_DataObject::factory('descarga_detalle');
	$do_descarga_detalle -> whereAdd('detalle_descarga_id = '.$_POST['id']);
	$do_descarga_detalle -> find();

	require_once('public/modales/ver_descarga.html');
	exit;
?>