<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_transp = DB_DataObject::factory('transportista');
	$do_transp -> transportista_nombre = $_POST['nombre'];

	if($do_transp -> find(true)){
		$respuesta = $do_transp -> transportista_id;
	}else{
		$respuesta = false;
	}

	echo(json_encode($respuesta));
?>