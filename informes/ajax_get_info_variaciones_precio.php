<?php
	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	// Estado actual de la caja
	$do_caja = DB_DataObject::factory('caja');
	$caja = $do_caja -> getUltimaCaja();
	$desde = $caja -> caja_fh_inicio;
	$hasta = date('Y-m-d H:i:s');

	// traigo todas las ventas que coincidan desde que se abrio la caja en adelante

	$venta = DB_DataObject::factory('venta');
	$venta_detalle = DB_DataObject::factory('venta_detalle');
	$venta_detalle -> joinAdd($venta);
	$venta_detalle -> whereAdd('detalle_prod_id = '.$_POST['id'].' AND detalle_prod_calibre = "'.$_POST['calibre'].'" AND (venta_estado_id = 2 or venta_estado_id = 4 ) AND venta_fh BETWEEN "'.$desde.'" AND "'.$hasta.'"');
    $venta_detalle -> find();
	 
	 $respuesta = array();
	 $venta_id = 0;
    while($venta_detalle -> fetch()){
    	
    	$respuesta[$venta_detalle -> detalle_prod_precio_u]["id"][$venta_detalle -> detalle_venta_id] = $venta_detalle -> detalle_venta_id ;
    	$respuesta[$venta_detalle -> detalle_prod_precio_u]['cantidad'] += $venta_detalle -> detalle_prod_cant;
    	if($venta_id != $venta_detalle -> detalle_venta_id ){
    		$respuesta[$venta_detalle -> detalle_prod_precio_u]['cantidad_ventas'] += 1;
    		$venta_id = $venta_detalle -> detalle_venta_id;
    	}
    }

	require_once('public/ajax_get_info_variaciones_precio.html');
	exit;
?>
