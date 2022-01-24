<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$fecha_actual = new DateTime();	
	$f_desde =  $fecha_actual -> modify("-1 month");

	$pago_exportad = DB_DataObject::factory('pago_exportador');

	if(!$_GET['fecha_desde']){
		$do_pagos = $pago_exportad -> getPagos($f_desde -> format('Y-m-d'),date('Y-m-d'));
		$campoFecha = date_format($f_desde,'d/m/Y').' - '.date('d/m/Y');
	} else {
		$do_pagos = $pago_exportad -> getPagos($_GET['fecha_desde'],$_GET['fecha_hasta']);
		$campoFecha = date('d/m/Y',strtotime($_GET['fecha_desde'])).' - '.date('d/m/Y',strtotime($_GET['fecha_hasta']));
	}


if($_POST['nuevo_pago']) {
		$pago = DB_DataObject::factory('pago_exportador');
		$id = $pago -> nuevoPago($_POST);
		//print_r($_POST);exit;
		header("Location: listado.php?id_pago=".$id); 
	}

	$do_exportad = DB_DataObject::factory('exportador');
	$do_exportad -> exportador_baja = 0;
	$do_exportad -> find();

	$exportadores = array();

	while ($do_exportad -> fetch()) { 
		$exportadores[$do_exportad -> exportador_id]['id'] = $do_exportad -> exportador_id;
		$exportadores[$do_exportad -> exportador_id]['nombre'] = $do_exportad -> exportador_nombre;
	}


	$do_banco = DB_DataObject::factory('banco');
	$do_banco -> banco_baja = 0;
	$do_banco -> find();

	$do_banco_et = DB_DataObject::factory('banco');
	$do_banco_et -> banco_baja = 0;
	$do_banco_et -> find();

	$do_banco_rt = DB_DataObject::factory('banco');
	$do_banco_rt -> banco_baja = 0;
	$do_banco_rt -> find();

	$do_banco_d = DB_DataObject::factory('banco');
	$do_banco_d -> banco_baja = 0;
	$do_banco_d -> find();

	$do_banco_ch3 = DB_DataObject::factory('banco');
	$do_banco_ch3 -> banco_baja = 0;
	$do_banco_ch3 -> find();

	$do_cheques_terceros = DB_DataObject::factory('cheque');
	$do_cheques_terceros -> joinAdd($do_banco_ch3);
	$do_cheques_terceros -> whereAdd('cheque_estado = 1');	// Pendiente de cobro o vencido
	$do_cheques_terceros -> find();

	$caja = DB_DataObject::factory('caja');
	$cajaAbierta = $caja -> cajaAbiertaHoy();
	
	require_once('public/listado_pagos.html');
	exit;
?>
