<?php
 
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');


	// Primero arreglar el JSON 

	$json = json_decode($_POST['data']);

	$objeto = array();

	foreach ($json as $k => $v) {
		$objeto[$v-> name] = $v-> value;
	}

	// Creo objeto

	$do_transp = DB_DataObject::factory('transportista');
	
	// Inserto cliente y cargo respuesta.

	$data['id'] = $do_transp -> nuevoTransportista($objeto);
	$data['nombre'] = $objeto['input_nombre'];

	print_r(json_encode($data));

?>