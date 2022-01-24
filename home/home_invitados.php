<?php
	require_once('../config/web.config');
	//require_once(AUTHFILE);
	
	require_once(CFG_PATH.'/data.config');
	// PEAR
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');
	
	// Traigo todos los items
	$items_menu = DB_DataObject::factory('documento');
	$items_menu -> doc_visible = 1;
	$items_menu -> orderBy('doc_orden');
	$items_menu -> find();

	require_once('../templates/templates/index_invitados.html');
	exit;
?>
