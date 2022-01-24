<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_ventas = DB_DataObject::factory('venta');
	$cantidad_ventas = $do_ventas -> getResumenCantidades();
	

	require_once('public/index.html');
	exit;
?>
