<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');


	$caja = DB_DataObject::factory('caja');
	$cajaAbierta = $caja -> cajaAbiertaHoy();

	$producto = DB_DataObject::factory('producto');

	if($_GET['fecha_desde']){
		$respuesta = $producto -> stockAgregado($_GET['fecha_desde'],$_GET['fecha_hasta']);
		$campoFecha = date('d/m/Y',strtotime($_GET['fecha_desde'])).' - '.date('d/m/Y',strtotime($_GET['fecha_hasta']));
	}else{
		$ultima_caja = $caja -> getUltimaCaja();
		$f_desde = $ultima_caja -> caja_fh_inicio;
		$f_hasta = date('Y-m-d 23:59:59');

		$respuesta = $producto -> stockAgregado($f_desde,$f_hasta);
		$campoFecha = date('d/m/Y',strtotime($f_desde)).' - '.date('d/m/Y');
	}
	

	require_once('public/stock_agregado.html');
	exit;
?>
