<?php
 
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');


	$do_venta = DB_DataObject::factory('venta');
	$id = $do_venta -> editarCliente($_POST['clienteid'],$_POST['ventaid']);

	$do_cliente = DB_DataObject::factory('cliente');
	$cliente = $do_cliente -> getClientes($_POST['clienteid']);
	echo $cliente -> cliente_nombre;
?>