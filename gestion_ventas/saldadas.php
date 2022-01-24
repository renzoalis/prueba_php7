<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	if($_POST['Despachar']) {
		$venta_e = DB_DataObject::factory('venta');
		$id_desp = $venta_e -> despacharVenta($_POST['edit_venta_id']);
		header("Location: saldadas.php?id_venta_desp=".$_POST['edit_venta_id'].'&busqueda='.$_POST['campo_busqueda']);
	}

	if($_POST['hidden-despachar']) {
		$ventas_despachadas = 0;
		if(!empty($_POST['check'])){
			foreach ($_POST['check'] as $k => $v) {
				$venta_e = DB_DataObject::factory('venta');
				$id_desp = $venta_e -> despacharVenta($k);
				$ventas_despachadas ++;
			}
			header("Location: saldadas.php?id_ventas_despa=".$ventas_despachadas.'&busqueda='.$_POST['campo_busqueda']);
		}
	}

	if($_POST['nuevo_concepto']) {
		$concepto = DB_DataObject::factory('venta_concepto');
		$id = $concepto -> nuevoCobro($_POST);
		$v = DB_DataObject::factory('venta');
		$v -> venta_id = $_POST['concepto_venta_id'];
		$v -> find(true);

		$concepto -> vc_venta_id = $_POST['concepto_venta_id'];
		$concepto -> vc_tipo = $_POST['combo_tipo'];
		$concepto -> vc_fh = $_POST['concepto_fh'];
		$concepto -> vc_monto = $_POST['input_monto'];
		$concepto -> vc_observacion = $_POST['input_obs_concepto'];
		$id = $concepto -> insert();

		header("Location: saldadas.php?id_concepto=".$id."&id_venta=".$_POST['concepto_venta_id']);
	}


	$do_concepto = DB_DataObject::factory('venta_concepto_tipo');
	$do_concepto -> find();

	$caja = DB_DataObject::factory('caja');
	$cajaAbierta = $caja -> cajaAbiertaHoy();

	$venta = DB_DataObject::factory('venta');

	if(!$_GET['fecha_desde']){
		$ultima_caja = $caja -> getUltimaCaja();
		$f_desde = $ultima_caja -> caja_fh_inicio;
		$f_hasta = date('Y-m-d 23:59:59');
		
		$do_ventas = $venta -> getVentasSaldadas($f_desde,date('Y-m-d H:i:s'));
		$campoFecha = date('d/m/Y',strtotime($f_desde)).' - '.date('d/m/Y');
	} else {
		$do_ventas = $venta -> getVentasSaldadas($_GET['fecha_desde'],$_GET['fecha_hasta']);
		$campoFecha = date('d/m/Y',strtotime($_GET['fecha_desde'])).' - '.date('d/m/Y',strtotime($_GET['fecha_hasta']));
	}

	$caja = DB_DataObject::factory('caja');
	$cajaAbierta = $caja -> cajaAbiertaHoy();

	// traigo objeto de la baso cobro_cliente para en la vista traer fecha cambio de estado a saldada
	$do_cobro = DB_DataObject::factory('cobro_cliente');

	require_once('public/saldadas.html');
	exit;
?>
