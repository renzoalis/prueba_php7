<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');
	
	$fecha_actual = new DateTime();	
	$f_desde =  $fecha_actual -> modify("-1 month");

	$do_perdida = DB_DataObject::factory('perdida_mercaderia');
	$perdidas = $do_perdida -> getListado($f_desde -> format('Y-m-d'),date('Y-m-d H:i:s'));
	
	// $caja = DB_DataObject::factory('caja');
	// $cajaAbierta = $caja -> cajaAbiertaHoy();

	require_once('public/listado_perdida.html');
	exit;
?>
