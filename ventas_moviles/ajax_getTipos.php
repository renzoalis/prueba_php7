<?php
	//header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');
	$do_tipos = DB_DataObject::factory('tipo');
	$tipos = $do_tipos -> tiposConStock();

	require_once('public/ajax_seleccionarTipo.html');
	exit;

?>