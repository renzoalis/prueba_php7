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

	$pago_emplead = DB_DataObject::factory('pago_empleado');

	if(!$_GET['fecha_desde']){
		$do_pagos = $pago_emplead -> getPagos($f_desde,$f_hasta);
		$campoFecha = date('d/m/Y',strtotime($f_desde)).' - '.date('d/m/Y');
	} else {
		$do_pagos = $pago_emplead -> getPagos($_GET['fecha_desde'],$_GET['fecha_hasta']);
		$campoFecha = date('d/m/Y',strtotime($_GET['fecha_desde'])).' - '.date('d/m/Y',strtotime($_GET['fecha_hasta']));
	}

	if($_POST['nuevo_pago']) {
		$pago = DB_DataObject::factory('pago_empleado');
		$id = $pago -> nuevoPago($_POST);
		//print_r($_POST);exit;
		header("Location: listado.php?id_pago=".$id); 
	}

	$do_emplead = DB_DataObject::factory('empleado');
	$do_emplead -> empleado_baja = 0;
	$do_emplead -> find();

	$empleados = array();

	while ($do_emplead -> fetch()) { 
		$empleados[$do_emplead -> empleado_id]['id'] = $do_emplead -> empleado_id;
		$empleados[$do_emplead -> empleado_id]['nombre'] = $do_emplead -> empleado_nombre;
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

	$meses[1] = "Enero";
	$meses[2] = "Febrero";
	$meses[3] = "Marzo";
	$meses[4] = "Abril";
	$meses[5] = "Mayo";
	$meses[6] = "Junio";
	$meses[7] = "Julio";
	$meses[8] = "Agosto";
	$meses[9] = "Septiembre";
	$meses[10] = "Octubre";
	$meses[11] = "Noviembre";
	$meses[12] = "Diciembre";


	$caja = DB_DataObject::factory('caja');
	$cajaAbierta = $caja -> cajaAbiertaHoy();

	$do_caja = DB_DataObject::factory('caja');
	$ultimaCaja =  $do_caja -> getUltimaCaja();
	$monto_caja = $do_caja -> getMontoEfectivo($ultimaCaja->caja_fh_inicio);

	require_once('public/listado_pagos.html');
	exit;
?>
