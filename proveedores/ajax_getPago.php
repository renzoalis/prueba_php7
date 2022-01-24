<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$pago_aux = DB_DataObject::factory('pago_proveedor');
	$pago_aux -> whereAdd('pago_id = '.$_POST['id']);
	$pago_aux -> find(true);


	$do_pago = DB_DataObject::factory('pago_proveedor');
	$do_pago -> whereAdd('pago_id = '.$_POST['id']);


	if($pago_aux -> pago_forma_pago == 6) {
		$do_cheque = DB_DataObject::factory('cheque');
		$do_pago -> joinAdd($do_cheque,"LEFT");
	}

	if($pago_aux -> pago_forma_pago == 2) {
		$do_cheque_propio = DB_DataObject::factory('cheque_propio');
		$do_pago -> joinAdd($do_cheque_propio,"LEFT");
	}

	$do_proveedor = DB_DataObject::factory('proveedor');
	$do_usuario = DB_DataObject::factory('usuario');
	$do_transferencia = DB_DataObject::factory('transferencia_bancaria');
	$do_deposito = DB_DataObject::factory('deposito_bancario');

	$do_pago -> joinAdd($do_usuario,"LEFT");
	$do_pago -> joinAdd($do_proveedor,"LEFT");
	$do_pago -> joinAdd($do_transferencia,"LEFT");
	$do_pago -> joinAdd($do_deposito,"LEFT");

	$do_pago -> find(true);


// Bancos
	$bancos = array();

	$do_banco = DB_DataObject::factory('banco');
	$do_banco -> banco_baja = 0;
	$do_banco -> find();

	while ($do_banco -> fetch()) { 
		$bancos[$do_banco -> banco_id]['id'] = $do_banco -> banco_id;
		$bancos[$do_banco -> banco_id]['nombre'] = $do_banco -> banco_nombre;
	}
// Bancos

// Proveedores
	$proveedores = array();

	$do_prov = DB_DataObject::factory('proveedor');
	$do_prov -> proveedor_baja = 0;
	$do_prov -> find();

	while ($do_prov -> fetch()) { 
		$proveedores[$do_prov -> prov_id]['id'] = $do_prov -> prov_id;
		$proveedores[$do_prov -> prov_id]['nombre'] = $do_prov -> prov_nombre;
	}
// Proveedores
	require_once('public/modales/ver_pago.html');
	exit;
?>