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

	$pago_aux = DB_DataObject::factory('pago_despachante');
	$pago_aux -> whereAdd('pago_id = '.$_POST['id']);
	$pago_aux -> find(true);

	$pago = DB_DataObject::factory('pago_despachante');
	$pago -> whereAdd('pago_id = '.$_POST['id']);
     
	
	$do_despachante = DB_DataObject::factory('despachante');
	$cheque_propio = DB_DataObject::factory('cheque_propio');
	$cheque = DB_DataObject::factory('cheque');
	$transferencia_bancaria_terceros = DB_DataObject::factory('transferencia_bancaria');
	$deposito_bancario_terceros = DB_DataObject::factory('deposito_bancario');


	$pago -> joinAdd($do_despachante);

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

	$do_despachante = DB_DataObject::factory('despachante');
	$do_despachante -> despachante_baja = 0;
	$do_despachante -> orderBy('despachante_nombre ASC');
	$do_despachante -> find();
	$despachantes = array();
    //print_r($pago);exit;
	while ($do_despachante -> fetch()) { 
		$despachantes[$do_despachante -> despachante_id]['id'] = $do_despachante -> despachante_id;
		$despachantes[$do_despachante -> despachante_id]['nombre'] = $do_despachante -> despachante_nombre;
	}
    // print_r($despachantes);exit;
	require_once('public/modales/ver_pago.html');
	exit;
?>