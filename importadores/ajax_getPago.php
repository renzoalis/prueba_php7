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

	$pago = DB_DataObject::factory('pago_importador');
	$pago -> whereAdd('pago_id = '.$_POST['id']);
     
	$do_importador = DB_DataObject::factory('importador');
	$pago -> joinAdd($do_importador);

	$pagochequepropio = DB_DataObject::factory('pago_importador');
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

	$do_importador = DB_DataObject::factory('importador');
	$do_importador -> importador_baja = 0;
	$do_importador -> orderBy('importador_nombre ASC');
	$do_importador -> find();
	$importadores = array();
    //print_r($pago);exit;
	while ($do_importador -> fetch()) { 
		$importadores[$do_importador -> importador_id]['id'] = $do_importador -> importador_id;
		$importadores[$do_importador -> importador_id]['nombre'] = $do_importador -> importador_nombre;
	}
    // print_r($importadores);exit;
	require_once('public/modales/ver_pago.html');
	exit;
?>