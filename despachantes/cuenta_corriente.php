<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_despachante = DB_DataObject::factory('despachante');
	$do_despachante -> despachante_baja = 0;
	$do_despachante -> orderBy('despachante_nombre ASC');
	$do_despachante -> find();

	$despachantes = array();

	while ($do_despachante -> fetch()) { 
		$despachantes[$do_despachante -> despachante_id]['id'] = $do_despachante -> despachante_id;
		$despachantes[$do_despachante -> despachante_id]['nombre'] = $do_despachante -> despachante_nombre;
	}


	if(!$_GET['fecha_desde']){
		$fecha_actual = new DateTime();	
		$f_desde =  $fecha_actual -> modify("-1 month");
		$campoFecha = date_format($f_desde,'d/m/Y').' - '.date('d/m/Y');
	}

	if($_GET['id_despachante']) {

		$ccte = DB_DataObject::factory('despachante_cuenta_corriente');

		if(!$_GET['fecha_desde']){
			$fecha_actual = new DateTime();	
			$f_desde =  $fecha_actual -> modify("-1 month");
			$dsd = date_format($f_desde,'d/m/Y');
			$hst = date('d/m/Y');
			$cc = $ccte -> despachanteGetCC($_GET['id_despachante'],$dsd,$hst);
			$campoFecha = date_format($f_desde,'d/m/Y').' - '.date('d/m/Y');
		} else {
			$cc = $ccte -> despachanteGetCC($_GET['id_despachante'],$_GET['fecha_desde'],$_GET['fecha_hasta']);
			$campoFecha = $_GET['fecha'];
		}

		$ccte2 = DB_DataObject::factory('despachante_cuenta_corriente');
		$saldo = $ccte2 -> getUltimaCC($_GET['id_despachante']);
		if($saldo -> ccte_saldo_actual < 0) {
			$class_cc = "cc_rojo";
		} else {
			$class_cc = "cc_verde";
		}
	}
	require_once('public/cc_despachantes.html');
	exit;
?>
