<?php
 
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	// Traigo cancha
 	$cancha = DB_DataObject::factory('cancha');
 	$cancha -> cancha_id = $_POST['id'];
 	$cancha -> find(true);

 	$respuesta = array();

 	$respuesta['id'] = $cancha -> cancha_id;
 	$respuesta['nombre'] = $cancha -> cancha_nombre;
 	$respuesta['valor'] = $cancha -> cancha_valor;
 
	echo json_encode($respuesta);

?>