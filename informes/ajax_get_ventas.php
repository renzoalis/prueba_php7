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

	$do_ventas = DB_DataObject::factory('venta');
	 foreach ($_POST['ids'] as $clave=>$valor) {
		$do_ventas -> whereAdd('(venta_estado_id = 2 or venta_estado_id = 4 ) AND venta_fh BETWEEN "'.$desde.'" AND "'.$hasta.'" AND venta_id = "'.$clave.'"','OR');
	
	 }
    

    $do_usuario = DB_DataObject::factory('usuario');
    $do_ventas -> joinAdd($do_usuario);

	$do_cliente = DB_DataObject::factory('cliente');
    $do_ventas -> joinAdd($do_cliente,"LEFT");

    $do_venta_estado = DB_DataObject::factory('venta_estado');
    $do_ventas -> joinAdd($do_venta_estado,"LEFT");
    
    $do_ventas -> find();

	require_once('public/ajax_get_ventas.html');
	exit;
?>