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

	$cobro = DB_DataObject::factory('cobro_cliente');
	$cobro -> whereAdd('cobro_id = '.$_POST['id']);

	$do_cliente = DB_DataObject::factory('cliente');
	$cobro -> joinAdd($do_cliente);

	$cheque = DB_DataObject::factory('cheque');
	$cobro -> joinAdd($cheque,"LEFT");

	$boleto = DB_DataObject::factory('boleto');
	$cobro -> joinAdd($boleto,"LEFT");

	$transferencia_bancaria_terceros = DB_DataObject::factory('transferencia_bancaria_terceros');
	$cobro -> joinAdd($transferencia_bancaria_terceros,"LEFT");

	$deposito_bancario_terceros = DB_DataObject::factory('deposito_bancario_terceros');
	$cobro -> joinAdd($deposito_bancario_terceros,"LEFT");

	$cobro -> find(true);

	$do_cli = DB_DataObject::factory('cliente');
	$do_cli -> cliente_baja = 0;
	$do_cli -> orderBy('cliente_nombre ASC');
	$do_cli -> find();
	$clientes = array();

	while ($do_cli -> fetch()) { 
		$clientes[$do_cli -> cliente_id]['id'] = $do_cli -> cliente_id;
		$clientes[$do_cli -> cliente_id]['nombre'] = $do_cli -> cliente_nombre;
	}

	require_once('public/modales/edit_cobro.html');
	exit;
?>