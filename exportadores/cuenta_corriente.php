<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_export = DB_DataObject::factory('exportador');
	$do_export -> exportador_baja = 0;
	$do_export -> orderBy('exportador_nombre ASC');
	$do_export -> find();

	$exportadores = array();

	while ($do_export -> fetch()) { 
		$exportadores[$do_export -> exportador_id]['id'] = $do_export -> exportador_id;
		$exportadores[$do_export -> exportador_id]['nombre'] = $do_export -> exportador_nombre;
	}

	if(!$_GET['fecha_desde']){
		$fecha_actual = new DateTime();	
		$f_desde =  $fecha_actual -> modify("-1 month");
		$campoFecha = date_format($f_desde,'d/m/Y').' - '.date('d/m/Y');
	}

	if($_GET['id_exportador']) {

		$ccte = DB_DataObject::factory('exportador_cuenta_corriente');

		if(!$_GET['fecha_desde']){
			$fecha_actual = new DateTime();	
			$f_desde =  $fecha_actual -> modify("-1 month");
			$dsd = date_format($f_desde,'d/m/Y');
			$hst = date('d/m/Y');
			$cc = $ccte -> exportadorGetCC($_GET['id_exportador'],$dsd,$hst);
			$campoFecha = date_format($f_desde,'d/m/Y').' - '.date('d/m/Y');
		} else {
			$cc = $ccte -> exportadorGetCC($_GET['id_exportador'],$_GET['fecha_desde'],$_GET['fecha_hasta']);
			$campoFecha = $_GET['fecha'];
		}

		$ccte2 = DB_DataObject::factory('exportador_cuenta_corriente');
		$saldo = $ccte2 -> getUltimaCC($_GET['id_exportador']);
		if($saldo -> ccte_saldo_actual < 0) {
			$class_cc = "cc_rojo";
		} else {
			$class_cc = "cc_verde";
		}
	}

	require_once('public/cc_exportadores.html');
	exit;
?>
