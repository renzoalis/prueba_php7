<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$premium = $_POST['premium'];

	$do_proveedores = DB_DataObject::factory('proveedor');
	$do_proveedores -> prov_id = $_POST['id'];
	$do_proveedores -> find(true);


	$ops = DB_DataObject::factory('cuenta_corriente_operacion');

	$do_cc = DB_DataObject::factory('proveedor_cuenta_corriente');
	$do_cc -> ccte_proveedor_id = $_POST['id'];
	$do_cc -> orderBy('ccte_id DESC, ccte_id DESC');
	$do_cc -> limit(20);
	$do_cc -> joinAdd($ops);
	$do_cc -> find();

	$do_cc_last = DB_DataObject::factory('proveedor_cuenta_corriente');
	$do_cc_last -> ccte_proveedor_id = $_POST['id'];
	$do_cc_last -> orderBy('ccte_id DESC');
	$do_cc_last -> find(true);
	if($do_cc_last -> ccte_saldo_actual < 0) {
		$class_cc = "cc_rojo";
	} else {
		$class_cc = "cc_verde";
	}
	
	require_once('public/modales/edit_proveedor.html');
	exit;
?>