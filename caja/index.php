<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$usr = DB_DataObject::factory('usuario');
	$usr -> usua_id = $_SESSION['usuario']['id'];
	$usr -> find(true);
	$premium = $usr -> esPremium();

	$do_caja_anterior = DB_DataObject::factory('caja');
	$saldo_anterior = $do_caja_anterior -> getUltimaCaja();
	
	$do_caja = DB_DataObject::factory('caja');

	// $caja = $do_caja -> getUltimaCaja();
	// $respuesta = $caja -> getDatosServicio();
	
	// print_r($respuesta);exit;

	if($_POST['nuevaCaja']) {
		$id = $do_caja -> abrirCaja($_POST);
		header("Location: index.php");  
	}

	if($_POST['cerrarCaja']) {
		$do_conciliacion = DB_DataObject::factory('conciliacion');
		$id = $do_conciliacion -> nuevaConciliacion($_POST['caja_id']);

		$id = $do_caja -> cerrarCaja($_POST); 


		header("Location: index.php?");  
	}

	// if($_POST['conciliarStock']) {
	// 	$do_conciliacion = DB_DataObject::factory('conciliacion');
	// 	$id = $do_conciliacion -> nuevaConciliacion($_POST);
	// 	header("Location: index.php");  
	// }

	$do_banco_ch3 = DB_DataObject::factory('banco');
	$do_banco_ch3 -> banco_baja = 0;
	$do_banco_ch3 -> find();

	$do_cheques_terceros = DB_DataObject::factory('cheque');
	$total_cheques = $do_cheques_terceros -> getMontoTotal();

	
	$do_cheques_terceros = DB_DataObject::factory('cheque');
	$do_cheques_terceros -> joinAdd($do_banco_ch3);
	$do_cheques_terceros -> whereAdd('cheque_estado = 1');	// Pendiente de cobro o vencido
	$do_cheques_terceros -> find();

	$fecha_actual = new DateTime();	
	$f =  $fecha_actual -> modify("+1 day");
	$maniana = date_format($f,'Y-m-d');

	require_once('public/index.html');
	exit;
?>
