<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_importadores = DB_DataObject::factory('importador');
	$do_importadores -> importador_nombre = $_POST['nombre'];

	if($do_importadores -> find(true)){
		$respuesta = $do_importadores -> importador_id;
	}else{
		$respuesta = false;
	}

	echo(json_encode($respuesta));
?>