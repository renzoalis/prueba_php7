<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_empleados = DB_DataObject::factory('empleado');
	$do_empleados -> empleado_nombre = $_POST['nombre'];

	if($do_empleados -> find(true)){
		$respuesta = $do_empleados -> empleado_id;
	}else{
		$respuesta = false;
	}

	echo(json_encode($respuesta));
?>