<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	// traigo todas la ventas de la caja
	$venta = DB_DataObject::factory('venta');
	$respuesta = $venta -> reporteOnline($desde,$hasta);

	require_once('public/reporteOnline.html');
	exit;
?>
