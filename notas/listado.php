<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');
	
	$caja = DB_DataObject::factory('caja');
	$cajaAbierta = $caja -> cajaAbiertaHoy();

	$notas = DB_DataObject::factory('notas');

	if(!$_GET['fecha_desde']){
		$ultima_caja = $caja -> getUltimaCaja();
		$f_desde = $ultima_caja -> caja_fh_inicio;
		$f_hasta = date('Y-m-d 23:59:59');
		
		$do_notas = $notas -> getNotas($f_desde,date('Y-m-d H:i:s'));
		$campoFecha = date('d/m/Y',strtotime($f_desde)).' - '.date('d/m/Y');
	} else {
		$do_notas = $notas -> getNotas($_GET['fecha_desde'],$_GET['fecha_hasta']);
		$campoFecha = date('d/m/Y',strtotime($_GET['fecha_desde'])).' - '.date('d/m/Y',strtotime($_GET['fecha_hasta']));
	}

    $notas -> orderBy('nota_id DESC');
	$notas -> find();

	require_once('public/listado_notas.html');
	exit;
?>
