<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_prov = DB_DataObject::factory('proveedor');
	$do_prov -> prov_nombre = $_POST['nombre'];

	if($do_prov -> find(true)){
		$respuesta = $do_prov -> prov_id;
	}else{
		$respuesta = false;
	}

	echo(json_encode($respuesta));
?>