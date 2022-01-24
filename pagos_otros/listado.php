<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');
	

	$caja = DB_DataObject::factory('caja');
	$cajaAbierta = $caja -> cajaAbiertaHoy();
	
	if($_GET['fecha_desde']){
		$f_desde = $_GET['fecha_desde'];
		$f_hasta = $_GET['fecha_hasta'];
	}else{
		$ultima_caja = $caja -> getUltimaCaja();
		$f_desde = $ultima_caja -> caja_fh_inicio;
		$f_hasta = date('Y-m-d 23:59:59');
	}


	$do_pagos_otros = DB_DataObject::factory('view_pagos_otros');
	if($f_desde){
		$do_pagos_otros -> whereAdd('FECHA between "'.$f_desde.'" and "'.$f_hasta.'"');
	}
	$do_pagos_otros -> find();

	// print_r($do_pago_otros);exit;
	require_once('public/listado_gastos.html');
	exit;
?>
