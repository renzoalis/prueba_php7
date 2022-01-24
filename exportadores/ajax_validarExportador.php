<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_exportadores = DB_DataObject::factory('exportador');
	$do_exportadores -> exportador_nombre = $_POST['nombre'];

	if($do_exportadores -> find(true)){
		$respuesta = $do_exportadores -> exportador_id;
	}else{
		$respuesta = false;
	}

	echo(json_encode($respuesta));
?>