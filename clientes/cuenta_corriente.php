<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_cli = DB_DataObject::factory('cliente');
	$do_cli -> cliente_baja = 0;
	$do_cli -> orderBy('cliente_nombre ASC');
	$do_cli -> find();

	$clientes = array();

	while ($do_cli -> fetch()) { 
		$clientes[$do_cli -> cliente_id]['id'] = $do_cli -> cliente_id;
		$clientes[$do_cli -> cliente_id]['nombre'] = $do_cli -> cliente_nombre;
	}

	if(!$_GET['fecha_desde']){
		$fecha_actual = new DateTime();	
		$f_desde =  $fecha_actual -> modify("-1 month");
		$campoFecha = date_format($f_desde,'d/m/Y').' - '.date('d/m/Y');
	}

	if($_GET['id_cliente']) {

		$ccte = DB_DataObject::factory('cliente_cuenta_corriente');
		
		if(!$_GET['fecha_desde']){
			$fecha_actual = new DateTime();	
			$f_desde =  $fecha_actual -> modify("-1 month");
			$dsd = date_format($f_desde,'d/m/Y');
			$hst = date('d/m/Y');
			$cc = $ccte -> clientesGetCC($_GET['id_cliente'],$dsd,$hst);
			$campoFecha = date_format($f_desde,'d/m/Y').' - '.date('d/m/Y');
		} else {
			$cc = $ccte -> clientesGetCC($_GET['id_cliente'],$_GET['fecha_desde'],$_GET['fecha_hasta']);
			$campoFecha = $_GET['fecha'];
		}
		

		$ccte2 = DB_DataObject::factory('cliente_cuenta_corriente');
		$saldo = $ccte2 -> getUltimaCC($_GET['id_cliente']);
		if($saldo -> ccte_saldo_actual < 0) {
			$class_cc = "cc_rojo";
		} else {
			$class_cc = "cc_verde";
		}
	}

	require_once('public/cc_clientes.html');
	exit;
?>
