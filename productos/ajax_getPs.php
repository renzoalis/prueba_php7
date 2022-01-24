<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_ps = DB_DataObject::factory('producto_stock');
	$datos = $do_ps -> getDetalleLote($_POST['id']);

	$do_ps2 = DB_DataObject::factory('producto_stock');
	$movs = $do_ps2 -> getMovimientosLote($_POST['id']);

	require_once('public/modales/edit_ps.html');
	exit;
?>