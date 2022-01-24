<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	// PEAR
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');
	
	// $permiso = ;

// --------------------------------							Tabla canchas						------------------------------------ //

	if($_POST['cancha_id']) {
		$cancha = DB_DataObject::factory('cancha');
		$id = $cancha -> modificarCancha($_POST);
		header("Location: configuracion.php");
	}

// --------------------------------							Tabla canchas						------------------------------------ //
	$do_canchas = DB_DataObject::factory('cancha');
	$do_canchas -> find();

// --------------------------------							Template							------------------------------------ //
	$activo['Suscripcion'] = 'active';
	require_once('public/configuracion.html');
	exit;
?>