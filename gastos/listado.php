<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');
	
	if($_POST['add_gasto']) {
	//print_r($_POST);exit;
		$gasto = DB_DataObject::factory('gasto');
		$gasto -> gasto_fh = date('Y-m-d H:i:s');
		$gasto -> gasto_categoria = $_POST['input_categoria'];
		$gasto -> gasto_concepto = $_POST['input_concepto'];
		$gasto -> gasto_monto_total = $_POST['input_monto'];
		$gasto -> gasto_usuario_id = $_SESSION['usuario']['id'];

		$gasto -> gasto_observacion = $_POST['input_observacion'];
		

		$id = $gasto -> insert();

		
		header("Location: listado.php?id_gasto=".$id);  
	}

	// $salidas_de_caja = array();

	// $pago_proveedor = DB_DataObject::factory('pago_proveedor');
	// $pago_proveedor -> getPagosArray();


	// $do_gastos = DB_DataObject::factory('gasto');
	// $do_gastos -> orderBy('gasto_id DESC');
	// $do_gastos -> find();
	
	$caja = DB_DataObject::factory('caja');
	$cajaAbierta = $caja -> cajaAbiertaHoy();
		
	$do_caja = DB_DataObject::factory('caja');
	$ultimaCaja =  $do_caja -> getUltimaCaja();
	$monto_caja = $do_caja -> getMontoEfectivo($ultimaCaja->caja_fh_inicio);

	if($_GET['fecha_desde']){
		$f_desde = $_GET['fecha_desde'];
		$f_hasta = $_GET['fecha_hasta'];
	}else{
		$ultima_caja = $caja -> getUltimaCaja();
		$f_desde = $ultima_caja -> caja_fh_inicio;
		$f_hasta = date('Y-m-d 23:59:59');
	}


	$do_salidas_de_caja = DB_DataObject::factory('view_salidas_de_caja');
	if($f_desde){
		$do_salidas_de_caja -> whereAdd('fecha between "'.$f_desde.'" and "'.$f_hasta.'"');
	}
	$do_salidas_de_caja -> find();
		// print_r($do_salidas_de_caja);
	$do_gasto_categoria = DB_DataObject::factory('gasto_categoria');
	$do_gasto_categoria -> gc_baja = 0;
	$do_gasto_categoria -> find();

	require_once('public/listado_gastos.html');
	exit;
?>
