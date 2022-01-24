<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	if($_POST['Archivar']) {
		$venta_e = DB_DataObject::factory('venta');
		$id_arch = $venta_e -> archivarVenta($_POST['arch_venta_id']);
		header("Location: despachadas.php?id_venta_arch=".$_POST['arch_venta_id'].'&busqueda='.$_POST['campo_busqueda']);
	}

	if($_POST['nuevo_concepto']) {
		//print_r($_POST);exit;
		$concepto = DB_DataObject::factory('venta_concepto');
		$id = $concepto -> nuevoConcepto($_POST);
		header("Location: despachadas.php?id_concepto=".$id."&id_venta=".$_POST['concepto_venta_id']);
	}
	
	if($_POST['nuevaObs']) {
		$par['concepto_venta_id'] = $_POST['obs_venta_id'];
		$par['tipo_concepto'] = 3;
		$par['input_obs_concepto'] = $_POST['nueva_obs'];

		$concepto = DB_DataObject::factory('venta_concepto');
		$id = $concepto -> nuevoConcepto($par);
		header("Location: despachadas.php?id_concepto=".$id."&id_venta=".$_POST['obs_venta_id']);
	}

	$caja = DB_DataObject::factory('caja');
	$cajaAbierta = $caja -> cajaAbiertaHoy();

	$do_concepto = DB_DataObject::factory('venta_concepto_tipo');
	$do_concepto -> whereAdd('vc_tipo_baja = 0');
	$do_concepto -> find();

	$venta = DB_DataObject::factory('venta');

	if(!$_GET['fecha_desde']){
		$ultima_caja = $caja -> getUltimaCaja();
		$f_desde = $ultima_caja -> caja_fh_inicio;
		$f_hasta = date('Y-m-d 23:59:59');

		$do_ventas = $venta -> getVentasDespachadas($f_desde,date('Y-m-d H:i:s'));
		$campoFecha = date('d/m/Y',strtotime($f_desde)).' - '.date('d/m/Y');
	} else {
		$do_ventas = $venta -> getVentasDespachadas($_GET['fecha_desde'],$_GET['fecha_hasta']);
		$campoFecha = date('d/m/Y',strtotime($_GET['fecha_desde'])).' - '.date('d/m/Y',strtotime($_GET['fecha_hasta']));
	}
	require_once('public/despachadas.html');
	exit;
?>
