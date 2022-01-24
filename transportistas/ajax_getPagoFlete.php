<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_pagoFlete = DB_DataObject::factory('flete');
	$do_pagoFlete -> whereAdd('flete_id = '.$_POST['id']);

	$do_transportista = DB_DataObject::factory('transportista');
	$do_pagoFlete -> joinAdd($do_transportista,"LEFT");

	$do_detalle_flete = DB_DataObject::factory('flete_detalle');
	$do_detalle_flete -> whereAdd('detalle_flete_id = '.$_POST['id']);

	$do_producto = DB_DataObject::factory('producto');
	$do_tipo = DB_DataObject::factory('tipo');
	$do_categoria = DB_DataObject::factory('categoria');

	$do_categoria -> joinAdd($do_tipo,"LEFT");
	$do_producto -> joinAdd($do_categoria,"LEFT");

	$do_detalle_flete -> joinAdd($do_producto,"LEFT");
	
	$do_detalle_flete -> find();
	$do_pagoFlete -> find(true);
	require_once('public/modales/ver_pago_flete.html');
	exit;
?>