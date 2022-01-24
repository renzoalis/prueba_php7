<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_venta_detalle = DB_DataObject::factory('venta_detalle');
	$do_venta_detalle -> whereAdd('detalle_venta_id = '.$_POST['id']);
	
	$do_venta_detalle -> find(); 

	$totalDescuento = 0;

	while ($do_venta_detalle -> fetch()) {
		$totalDescuento += $do_venta_detalle -> detalle_descuento_parcial;
	}

	echo $totalDescuento;

?>