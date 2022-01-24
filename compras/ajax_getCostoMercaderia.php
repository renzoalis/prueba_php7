<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$compra = DB_DataObject::factory('compra');
	$compra -> whereAdd('compra_id = '.$_POST['compra_id']);
	$compra -> find(true);

	// Traigo detalle de la compra
	$do_compra_detalle = DB_DataObject::factory('compra_detalle');
	$do_compra_detalle -> whereAdd('detalle_compra_id = '.$_POST['compra_id']);
	
	$do_producto = DB_DataObject::factory('producto');
	$do_tipo = DB_DataObject::factory('tipo');
	$do_categoria = DB_DataObject::factory('categoria');
	$do_producto_stock = DB_DataObject::factory('producto_stock');

	$do_categoria -> joinAdd($do_tipo,"LEFT");
	$do_producto -> joinAdd($do_categoria,"LEFT");
	$do_compra_detalle -> joinAdd($do_producto,"LEFT");
	$do_compra_detalle -> joinAdd($do_producto_stock,"LEFT");
	
	$do_compra_detalle -> find(); 

	$ps = DB_DataObject::factory('producto_stock');
	$ps -> ps_compra_id = $_POST['compra_id'];
	$ps -> find();

	while ($ps -> fetch()) {
		$cantidades[$ps -> ps_producto_id][$ps -> ps_calibre] = $ps -> ps_cantidad;
	}

	while ($do_compra_detalle -> fetch()) { 

		$detalle[$do_compra_detalle -> detalle_id]['id'] = $do_compra_detalle -> detalle_prod_id;
		$detalle[$do_compra_detalle -> detalle_id]['det_id'] = $do_compra_detalle -> detalle_id;
		$detalle[$do_compra_detalle -> detalle_id]['ps_id'] = $do_compra_detalle -> detalle_ps_id;
		$detalle[$do_compra_detalle -> detalle_id]['prod_id'] = $do_compra_detalle -> detalle_prod_id;
		$detalle[$do_compra_detalle -> detalle_id]['tipo_id'] = $do_compra_detalle -> tipo_id;
		$detalle[$do_compra_detalle -> detalle_id]['tipo_nombre'] = $do_compra_detalle -> tipo_nombre;
		$detalle[$do_compra_detalle -> detalle_id]['cat_id'] = $do_compra_detalle -> cat_id;
		$detalle[$do_compra_detalle -> detalle_id]['cat_nombre'] = $do_compra_detalle -> cat_nombre;
		$detalle[$do_compra_detalle -> detalle_id]['prod_calibre'] = $do_compra_detalle -> detalle_prod_calibre;
		$detalle[$do_compra_detalle -> detalle_id]['prod_nombre'] = $do_compra_detalle -> prod_nombre;
		$detalle[$do_compra_detalle -> detalle_id]['prod_cant'] = $do_compra_detalle -> detalle_prod_cant;
		$detalle[$do_compra_detalle -> detalle_id]['prod_val'] = ($do_compra_detalle -> detalle_prod_precio_u * $do_compra_detalle -> detalle_prod_cant - $do_compra_detalle -> detalle_descuento_parcial) / $do_compra_detalle -> detalle_prod_cant;
		$detalle[$do_compra_detalle -> detalle_id]['prod_desc'] = $do_compra_detalle -> detalle_descuento_parcial;
		$detalle[$do_compra_detalle -> detalle_id]['prod_tot'] = ($do_compra_detalle -> detalle_prod_precio_u * $do_compra_detalle -> detalle_prod_cant) - $do_compra_detalle -> detalle_descuento_parcial;

	}

	require_once('public/modales/nuevo_costo_mercaderia.html');
	exit;
?>