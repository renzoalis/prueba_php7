<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	
	$caja = DB_DataObject::factory('caja');
	$cajaAbierta = $caja -> cajaAbiertaHoy();

	$ultima_caja = $caja -> getUltimaCaja();
	$f_desde = $ultima_caja -> caja_fh_inicio;
	$f_hasta = date('Y-m-d 23:59:59');


	$pago_importad = DB_DataObject::factory('pago_cardesc');

	if(!$_GET['fecha_desde']){
		$do_pagos = $pago_importad -> getPagos($f_desde,$f_hasta);
		$campoFecha = date('d/m/Y',strtotime($f_desde)).' - '.date('d/m/Y');
	} else {
		$do_pagos = $pago_importad -> getPagos($_GET['fecha_desde'],$_GET['fecha_hasta']);
		$campoFecha = date('d/m/Y',strtotime($_GET['fecha_desde'])).' - '.date('d/m/Y',strtotime($_GET['fecha_hasta']));
	}


	if($_POST['nuevo_pago']) {
		$pago = DB_DataObject::factory('pago_cardesc');
		$id = $pago -> nuevoPago($_POST);
		//print_r($_POST);exit;
		header("Location: listado.php?id_pago=".$id); 
	}

	$do_cardesc = DB_DataObject::factory('cardesc');
	$do_cardesc -> cardesc_baja = 0;
	$do_cardesc -> find();

	$cargadescarga = array();

	while ($do_cardesc -> fetch()) { 
		$cargadescarga[$do_cardesc -> cardesc_id]['id'] = $do_cardesc -> cardesc_id;
		$cargadescarga[$do_cardesc -> cardesc_id]['nombre'] = $do_cardesc -> cardesc_nombre;
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

	$do_caja = DB_DataObject::factory('caja');
	$ultimaCaja =  $do_caja -> getUltimaCaja();
	$monto_caja = $do_caja -> getMontoEfectivo($ultimaCaja->caja_fh_inicio);

	require_once('public/listado_pagos.html');
	exit;
?>
