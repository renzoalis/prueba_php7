<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_despachantes = DB_DataObject::factory('despachante');
	$do_despachantes -> despachante_nombre = $_POST['nombre'];

	if($do_despachantes -> find(true)){
		$respuesta = $do_despachantes -> despachante_id;
	}else{
		$respuesta = false;
	}

	echo(json_encode($respuesta));
?>