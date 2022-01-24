<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$venta = DB_DataObject::factory('venta');
	$venta -> whereAdd('venta_id = '.$_POST['venta_id']);
	$venta -> find(true);

	// Traigo detalle de la venta
	$do_venta_detalle = DB_DataObject::factory('venta_detalle');
	$do_venta_detalle -> whereAdd('detalle_venta_id = '.$_POST['venta_id']);
	
	$do_producto = DB_DataObject::factory('producto');
	$do_tipo = DB_DataObject::factory('tipo');
	$do_categoria = DB_DataObject::factory('categoria');
	$do_producto_stock = DB_DataObject::factory('producto_stock');

	$do_categoria -> joinAdd($do_tipo,"LEFT");
	$do_producto -> joinAdd($do_categoria,"LEFT");
	$do_venta_detalle -> joinAdd($do_producto,"LEFT");
	$do_venta_detalle -> joinAdd($do_producto_stock,"LEFT");
	
	$do_venta_detalle -> find(); 

	$ps = DB_DataObject::factory('producto_stock');
	$ps -> ps_venta_id = $_POST['venta_id'];
	$ps -> find();

	while ($ps -> fetch()) {
		$cantidades[$ps -> ps_producto_id][$ps -> ps_calibre] = $ps -> ps_cantidad;
	}

	while ($do_venta_detalle -> fetch()) { 

		$detalle[$do_venta_detalle -> detalle_id]['id'] = $do_venta_detalle -> detalle_prod_id;
		$detalle[$do_venta_detalle -> detalle_id]['det_id'] = $do_venta_detalle -> detalle_id;
		$detalle[$do_venta_detalle -> detalle_id]['prod_id'] = $do_venta_detalle -> detalle_prod_id;
		$detalle[$do_venta_detalle -> detalle_id]['tipo_id'] = $do_venta_detalle -> tipo_id;
		$detalle[$do_venta_detalle -> detalle_id]['tipo_nombre'] = $do_venta_detalle -> tipo_nombre;
		$detalle[$do_venta_detalle -> detalle_id]['cat_id'] = $do_venta_detalle -> cat_id;
		$detalle[$do_venta_detalle -> detalle_id]['cat_nombre'] = $do_venta_detalle -> cat_nombre;
		$detalle[$do_venta_detalle -> detalle_id]['prod_calibre'] = $do_venta_detalle -> detalle_prod_calibre;
		$detalle[$do_venta_detalle -> detalle_id]['prod_nombre'] = $do_venta_detalle -> prod_nombre;
		$detalle[$do_venta_detalle -> detalle_id]['prod_cant'] = $do_venta_detalle -> detalle_prod_cant;
		$detalle[$do_venta_detalle -> detalle_id]['prod_val'] = ($do_venta_detalle -> detalle_prod_precio_u * $do_venta_detalle -> detalle_prod_cant - $do_venta_detalle -> detalle_descuento_parcial) / $do_venta_detalle -> detalle_prod_cant;
		$detalle[$do_venta_detalle -> detalle_id]['prod_desc'] = $do_venta_detalle -> detalle_descuento_parcial;
		$detalle[$do_venta_detalle -> detalle_id]['prod_tot'] = ($do_venta_detalle -> detalle_prod_precio_u * $do_venta_detalle -> detalle_prod_cant) - $do_venta_detalle -> detalle_descuento_parcial;

	}

	require_once('public/modales/nueva_descarga.html');
	exit;
?>