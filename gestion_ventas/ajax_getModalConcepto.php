<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_cobro_cliente = DB_DataObject::factory('cobro_cliente');
	$do_cobro_cliente -> cobro_venta_id = $_POST['venta_id'];
	$do_cobro_cliente -> find(true);

	$boleto_id = $do_cobro_cliente -> cobro_bono_id;

	$do_concepto = DB_DataObject::factory('venta_concepto_tipo');
	$do_concepto -> whereAdd('vc_tipo_baja = 0');
	$do_concepto -> find();

	$venta_id = $_POST['venta_id'];
	$cliente_id = $_POST['cliente_id'];
	
	require_once('public/modales/nueva_devolucion.html');
	exit;
?>