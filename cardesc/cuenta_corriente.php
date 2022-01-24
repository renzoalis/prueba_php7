<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_import = DB_DataObject::factory('cardesc');
	$do_import -> cardesc_baja = 0;
	$do_import -> orderBy('cardesc_nombre ASC');
	$do_import -> find();

	$importadores = array();

	while ($do_import -> fetch()) { 
		$importadores[$do_import -> cardesc_id]['id'] = $do_import -> cardesc_id;
		$importadores[$do_import -> cardesc_id]['nombre'] = $do_import -> cardesc_nombre;
	}

	if(!$_GET['fecha_desde']){
		$fecha_actual = new DateTime();	
		$f_desde =  $fecha_actual -> modify("-1 month");
		$campoFecha = date_format($f_desde,'d/m/Y').' - '.date('d/m/Y');
	}

	if($_GET['id_importador']) {

		$ccte = DB_DataObject::factory('cardesc_cuenta_corriente');

		if(!$_GET['fecha_desde']){
			$fecha_actual = new DateTime();	
			$f_desde =  $fecha_actual -> modify("-1 month");
			$dsd = date_format($f_desde,'d/m/Y');
			$hst = date('d/m/Y');
			$cc = $ccte -> cardescGetCC($_GET['id_importador'],$dsd,$hst);
			$campoFecha = date_format($f_desde,'d/m/Y').' - '.date('d/m/Y');
		} else {
			$cc = $ccte -> cardescGetCC($_GET['id_importador'],$_GET['fecha_desde'],$_GET['fecha_hasta']);
			$campoFecha = $_GET['fecha'];
		}

		$ccte2 = DB_DataObject::factory('cardesc_cuenta_corriente');
		$saldo = $ccte2 -> getUltimaCC($_GET['id_importador']);
		if($saldo -> ccte_saldo_actual < 0) {
			$class_cc = "cc_rojo";
		} else {
			$class_cc = "cc_verde";
		}
	}
	// print_r($cc);exxit;
	require_once('public/cc_cardesc.html');
	exit;
?>
