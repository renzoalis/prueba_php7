<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_clientes = DB_DataObject::factory('cliente');
	$do_clientes -> cliente_nombre = $_POST['nombre'];

	if($do_clientes -> find(true)){
		$respuesta = $do_clientes -> cliente_id;
	}else{
		$respuesta = false;
	}

	echo(json_encode($respuesta));
?>