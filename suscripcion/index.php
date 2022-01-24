<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	// PEAR
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');
	
	// $permiso = ;
// --------------------------------					Cambio de suscripción					 ------------------------------------ //

	if ($_POST['alta_ticket']){
		$ticket = DB_DataObject::factory('ticket');
		$ticket_id = $ticket -> agregar_ticket($_POST);
		header("Location: index.php?id_ticket=".$ticket_id);
	}

// --------------------------------	Tipo de suscripción, Estado de cuenta, Próximo vencimiento ------------------------------------ //
	
	$param = DB_DataObject::factory('_parametros'); 
	$tipo_suscrip = $param -> getSuscripcion();

// --------------------------------							Tabla pagos 						------------------------------------ //
	$_p = DB_DataObject::factory('_pagos');
	$pagos = $_p -> getPagos(); 
	$_p1 = DB_DataObject::factory('_pagos');
	$estado_cuenta = $_p1 -> getEstadoCuenta();
	$_p2 = DB_DataObject::factory('_pagos');
	$prox_v = $_p2 -> getProxVencimiento();

// --------------------------------						Template								------------------------------------ //
	$activo['Suscripcion'] = 'active';
	require_once('public/index.html');
	exit;
?>