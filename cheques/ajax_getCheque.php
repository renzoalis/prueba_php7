<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_cheques = DB_DataObject::factory('cheque');

	$do_banco = DB_DataObject::factory('banco');
    $do_cliente = DB_DataObject::factory('cliente');
	$do_proveedor = DB_DataObject::factory('proveedor');
	$do_transportista = DB_DataObject::factory('transportista');
	$do_despachante = DB_DataObject::factory('despachante');
	$do_importador = DB_DataObject::factory('importador');
	$do_exportador = DB_DataObject::factory('exportador');

    $do_cheques -> joinAdd($do_cliente);
    $do_cheques -> joinAdd($do_banco);
    $do_cheques -> joinAdd($do_proveedor,'LEFT');
    $do_cheques -> joinAdd($do_transportista,'LEFT');
    $do_cheques -> joinAdd($do_despachante,'LEFT');
    $do_cheques -> joinAdd($do_exportador,'LEFT');
    $do_cheques -> joinAdd($do_importador,'LEFT');

	$do_cheques -> cheque_id = $_POST['id'];
	$do_cheques -> find(true);

	$do_estados = DB_DataObject::factory('cheque_estado');
	$do_estados -> find();

	$caja = DB_DataObject::factory('caja');
	$cajaAbierta = $caja -> cajaAbiertaHoy();

	require_once('public/modales/edit_cheque.html');
	exit;
?>