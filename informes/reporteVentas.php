<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	// traigo todas la ventas de la caja
	$caja = DB_DataObject::factory('caja');
	$cajaAbierta = $caja -> cajaAbiertaHoy();

	$reporte_ventas = DB_DataObject::factory('view_reporte_ventas');

	if($_GET['fecha_desde']){
		$reporte_ventas -> whereAdd('FECHA BETWEEN "'.$_GET['fecha_desde'].'" AND "'.date('Y-m-d H:i:s').'"');
		$reporte_ventas -> find();
		$campoFecha = date('d/m/Y',strtotime($_GET['fecha_desde'])).' - '.date('d/m/Y',strtotime($_GET['fecha_hasta']));
	}else{
		$ultima_caja = $caja -> getUltimaCaja();
		$f_desde = $ultima_caja -> caja_fh_inicio;
		$f_hasta = date('Y-m-d 23:59:59');

		$reporte_ventas -> whereAdd('FECHA BETWEEN "'.$f_desde.'" AND "'.$f_hasta.'"');
		$reporte_ventas -> find();
		$campoFecha = date('d/m/Y',strtotime($f_desde)).' - '.date('d/m/Y');
	}


// print_r($venta);

	require_once('public/reporteVentas.html');
	exit;
?>
