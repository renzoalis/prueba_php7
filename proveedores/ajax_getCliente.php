<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_clientes = DB_DataObject::factory('cliente');
	$do_clientes -> cliente_id = $_POST['id'];
	$do_clientes -> find(true);

	$ops = DB_DataObject::factory('cuenta_corriente_operacion');

	$do_cc = DB_DataObject::factory('cliente_cuenta_corriente');
	$do_cc -> ccte_cliente_id = $_POST['id'];
	$do_cc -> orderBy('ccte_id DESC');
	$do_cc -> limit(50);
	$do_cc -> joinAdd($ops);
	$do_cc -> find();

	$do_cc_last = DB_DataObject::factory('cliente_cuenta_corriente');
	$do_cc_last -> ccte_cliente_id = $_POST['id'];
	$do_cc_last -> orderBy('ccte_id DESC');
	$do_cc_last -> find(true);
	if($do_cc_last -> ccte_saldo_actual < 0) {
		$class_cc = "cc_rojo";
	} else {
		$class_cc = "cc_verde";
	}

	require_once('public/modales/edit_cliente.html');
	exit;
?>