<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_tipos = DB_DataObject::factory('tipo');
	$do_tipos -> tipo_nombre = $_POST['nombre'];

	if($do_tipos -> find(true)){
		$respuesta = $do_tipos -> tipo_id;
	}else{
		$respuesta = false;
	}

	echo(json_encode($respuesta));
?>