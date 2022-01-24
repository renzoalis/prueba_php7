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

	$pago_transp = DB_DataObject::factory('pago_transportista');

	if(!$_GET['fecha_desde']){
		$do_pagos = $pago_transp -> getPagos($f_desde, $f_hasta);
		$campoFecha = date('d/m/Y',strtotime($f_desde)).' - '.date('d/m/Y');
	} else {
		$do_pagos = $pago_transp -> getPagos($_GET['fecha_desde'],$_GET['fecha_hasta']);
		$campoFecha = date('d/m/Y',strtotime($_GET['fecha_desde'])).' - '.date('d/m/Y',strtotime($_GET['fecha_hasta']));
	}



	if($_POST['nuevo_pago']) {
		
		$pagos = DB_DataObject::factory('pago_transportista');
		$id = $pagos -> nuevoPago($_POST);
		//print_r($_POST);exit;
		header("Location: listado.php?id_pago=".$id); 
	}

	$do_transp = DB_DataObject::factory('transportista');
	$do_transp -> transportista_baja = 0;
	$do_transp -> find();

	$transportistas = array();

	while ($do_transp -> fetch()) { 
		$transportistas[$do_transp -> transportista_id]['id'] = $do_transp -> transportista_id;
		$transportistas[$do_transp -> transportista_id]['nombre'] = $do_transp -> transportista_nombre;
	}


	$do_banco_listado = DB_DataObject::factory('banco');
	$do_banco_listado -> banco_baja = 0;
	$do_banco_listado -> find();

	$do_banco_et_listado = DB_DataObject::factory('banco');
	$do_banco_et_listado -> banco_baja = 0;
	$do_banco_et_listado -> find();

	$do_banco_rt_listado = DB_DataObject::factory('banco');
	$do_banco_rt_listado -> banco_baja = 0;
	$do_banco_rt_listado -> find();

	$do_banco_d_listado = DB_DataObject::factory('banco');
	$do_banco_d_listado -> banco_baja = 0;
	$do_banco_d_listado -> find();

	$do_banco_ch3_listado = DB_DataObject::factory('banco');
	$do_banco_ch3_listado -> banco_baja = 0;
	$do_banco_ch3_listado -> find();

	$do_cheques_terceros_listado = DB_DataObject::factory('cheque');
	$do_cheques_terceros_listado -> joinAdd($do_banco_ch3_listado);
	$do_cheques_terceros_listado -> whereAdd('cheque_estado = 1');	// Pendiente de cobro o vencido
	$do_cheques_terceros_listado -> find();

	$caja = DB_DataObject::factory('caja');
	$cajaAbierta = $caja -> cajaAbiertaHoy();

	$do_caja = DB_DataObject::factory('caja');
	$ultimaCaja =  $do_caja -> getUltimaCaja();
	$monto_caja = $do_caja -> getMontoEfectivo($ultimaCaja->caja_fh_inicio);

	require_once('public/listado_pagos.html');
	exit;
?>
