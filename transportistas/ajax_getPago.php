<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_banco = DB_DataObject::factory('banco');
	$do_banco -> banco_baja = 0;
	$do_banco -> find();

	$do_banco2 = DB_DataObject::factory('banco');
	$do_banco2 -> banco_baja = 0;
	$do_banco2 -> find();

	$do_banco_et = DB_DataObject::factory('banco');
	$do_banco_et -> banco_baja = 0;
	$do_banco_et -> find();

	$do_banco_rt = DB_DataObject::factory('banco');
	$do_banco_rt -> banco_baja = 0;
	$do_banco_rt -> find();

	$do_banco_d = DB_DataObject::factory('banco');
	$do_banco_d -> banco_baja = 0;
	$do_banco_d -> find();

	$pago = DB_DataObject::factory('pago_transportista');
	$pago -> whereAdd('pago_id = '.$_POST['id']);
     
	$do_transportista = DB_DataObject::factory('transportista');
	$pago -> joinAdd($do_transportista);

	$pagochequepropio = DB_DataObject::factory('pago_transportista');
	$pagochequepropio -> whereAdd('pago_id = '.$_POST['id']);
	
	$cheque_propio = DB_DataObject::factory('cheque_propio');
	$pagochequepropio -> joinAdd($cheque_propio);
	$pagochequepropio-> find(true);

	$cheque = DB_DataObject::factory('cheque');
	$pago -> joinAdd($cheque,"LEFT");
	
	
	//$boleto = DB_DataObject::factory('boleto');
	//$pago -> joinAdd($boleto,"LEFT");

	$transferencia_bancaria_terceros = DB_DataObject::factory('transferencia_bancaria');
	$pago -> joinAdd($transferencia_bancaria_terceros,"LEFT");

	$deposito_bancario_terceros = DB_DataObject::factory('deposito_bancario');
	$pago -> joinAdd($deposito_bancario_terceros,"LEFT");

	$pago -> find(true);

	$do_transportista = DB_DataObject::factory('transportista');
	$do_transportista -> transportista_baja = 0;
	$do_transportista -> orderBy('transportista_nombre ASC');
	$do_transportista -> find();
	$transportistas = array();
    //print_r($pago);exit;
	while ($do_transportista -> fetch()) { 
		$transportistas[$do_transportista -> transportista_id]['id'] = $do_transportista -> transportista_id;
		$transportistas[$do_transportista -> transportista_id]['nombre'] = $do_transportista -> transportista_nombre;
	}
    // print_r($transportistas);exit;
	require_once('public/modales/ver_pago.html');
	exit;
?>