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

	$pago_aux = DB_DataObject::factory('pago_transportista');
	$pago_aux -> whereAdd('pago_id = '.$_POST['id']);
	$pago_aux -> find(true);

	$pago = DB_DataObject::factory('pago_transportista');
	$pago -> whereAdd('pago_id = '.$_POST['id']);
     
	
	$do_transportista = DB_DataObject::factory('transportista');
	$cheque_propio = DB_DataObject::factory('cheque_propio');
	$cheque = DB_DataObject::factory('cheque');
	$transferencia_bancaria_terceros = DB_DataObject::factory('transferencia_bancaria');
	$deposito_bancario_terceros = DB_DataObject::factory('deposito_bancario');


	$pago -> joinAdd($do_transportista);

	if($pago_aux -> pago_forma_pago == 2){				//CHEQUE PROPIO
		$pago -> joinAdd($cheque_propio,"LEFT");
	}elseif($pago_aux -> pago_forma_pago == 6) {		//CHEQUE PROPIO
		$pago -> joinAdd($cheque,"LEFT");
	}
	
	$pago -> joinAdd($transferencia_bancaria_terceros,"LEFT");
	$pago -> joinAdd($deposito_bancario_terceros,"LEFT");
	//$boleto = DB_DataObject::factory('boleto');
	//$pago -> joinAdd($boleto,"LEFT");



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
     //print_r($pago);exit;
	require_once('public/modales/ver_pago.html');
	exit;
?>