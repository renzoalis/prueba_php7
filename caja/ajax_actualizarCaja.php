<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	// Param iniciales
		$fecha_filtro = date('Y-m-d');
		// $prem = $_POST['premium'];
	
	// Declaracion de objetos
		$do_caja = DB_DataObject::factory('caja');
		$venta = DB_DataObject::factory('venta');

	// Estado actual de la caja
		$caja = $do_caja -> getUltimaCaja();

	// VENTAS / BULTOS VENDIDOS / BULTOS DESPACHADOS
		$estadistica_ventas = $venta -> getBultosDiariosCaja($caja -> caja_fh_inicio, $caja -> caja_fh_cierre);

	// Datos crudos de ingresos/egresos
		$contable = $caja -> getDatosCaja($caja -> caja_fh_inicio, $caja -> caja_fh_cierre);
		
	$u_abrio = DB_DataObject::factory('usuario');
	$u_abrio -> usua_id = $caja -> caja_usua_inicio;
	$u_abrio -> find(true);

	$venta_c = DB_DataObject::factory('venta');
	$ventas_sin_despachar = $venta_c -> getCantidadVentas(1) + $venta_c -> getCantidadVentas(2);

	$compra_c = DB_DataObject::factory('compra');
	$compras_sin_costos = $compra_c -> getCantidadSinCostos($caja -> caja_fh_inicio, $caja -> caja_fh_cierre);

	$transferencias_c = DB_DataObject::factory('transferencias');
	$transferencias_sin_costos = $transferencias_c -> getCantidadSinCostos($caja -> caja_fh_inicio, $caja -> caja_fh_cierre);
	// print_r($compras_sin_costos);exit;

	$do_caja = DB_DataObject::factory('caja');
	$caja = $do_caja -> getUltimaCaja();

	$movimientos_efectivo = $caja -> getNuevosDatosCaja($caja -> caja_fh_inicio, $caja -> caja_fh_cierre);

	require_once('public/datos_caja.html');
	exit;
?>