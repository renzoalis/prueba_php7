<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	// traigo todas la ventas de la caja
	$caja = DB_DataObject::factory('caja');
	$cajaAbierta = $caja -> cajaAbiertaHoy();

	$do_diferencia_mercaderia = DB_DataObject::factory('diferencia_mercaderia');
	$do_usuario = DB_DataObject::factory('usuario');
	$do_diferencia_mercaderia -> joinAdd($do_usuario);

	if($_GET['fecha_desde']){
		$do_diferencia_mercaderia -> whereAdd('dif_fh BETWEEN "'.$_GET['fecha_desde'].'" AND "'.date('Y-m-d H:i:s').'" AND dif_restauro_stock = 0');
		$do_diferencia_mercaderia -> find();
		$campoFecha = date('d/m/Y',strtotime($_GET['fecha_desde'])).' - '.date('d/m/Y',strtotime($_GET['fecha_hasta']));
	}else{
		$ultima_caja = $caja -> getUltimaCaja();
		$f_desde = $ultima_caja -> caja_fh_inicio;
		$f_hasta = date('Y-m-d 23:59:59');

		$do_diferencia_mercaderia -> whereAdd('dif_fh BETWEEN "'.$f_desde.'" AND "'.$f_hasta.'" AND dif_restauro_stock = 0');
		$do_diferencia_mercaderia -> find();
		$campoFecha = date('d/m/Y',strtotime($f_desde)).' - '.date('d/m/Y');
	}


// print_r($do_diferencia_mercaderia);exit;

	require_once('public/reportePerdidas.html');
	exit;
?>
