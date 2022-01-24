<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_cobro_cliente = DB_DataObject::factory('cobro_cliente');
	$do_cobro_cliente -> cobro_venta_id = $_POST['venta_id'];
	$do_cobro_cliente -> find(true);

	$boleto_id = $do_cobro_cliente -> cobro_bono_id;

	$venta_id = $_POST['venta_id'];
	$cliente_id = $_POST['cliente_id'];

	$do_cliente = DB_DataObject::factory('cliente');
	$do_cliente -> cliente_id = $_POST['cliente_id'];
	$do_cliente -> find(true);

	/* Pego todo lo que estaba en el ajax anterior de devolucion mercaderia */
	$do_venta_detalle = DB_DataObject::factory('venta_detalle');
	$do_venta_detalle -> whereAdd('detalle_venta_id = '.$venta_id);
	
	$do_producto = DB_DataObject::factory('producto');
	$do_tipo = DB_DataObject::factory('tipo');
	$do_categoria = DB_DataObject::factory('categoria');
	$do_producto_stock = DB_DataObject::factory('producto_stock');
	$do_venta_detalle_stock = DB_DataObject::factory('venta_detalle_stock');

	$do_categoria -> joinAdd($do_tipo,"LEFT");
	$do_producto -> joinAdd($do_categoria,"LEFT");
	$do_venta_detalle -> joinAdd($do_producto,"LEFT");
	$do_venta_detalle -> joinAdd($do_producto_stock,"LEFT");
	$do_venta_detalle -> joinAdd($do_venta_detalle_stock,"LEFT");
	
	$do_venta_detalle -> find(); 

	while ($do_venta_detalle -> fetch()) { 
		if($do_venta_detalle -> ps_precio_prom_venta == 0){		//Si ya tiene calculado el ppv no lo muestro para devolver
			$detalle[$do_venta_detalle -> detalle_id]['det_id'] = $do_venta_detalle -> detalle_id;
			$detalle[$do_venta_detalle -> detalle_id]['prod_id'] = $do_venta_detalle -> detalle_prod_id;
			$detalle[$do_venta_detalle -> detalle_id]['tipo_id'] = $do_venta_detalle -> tipo_id;
			$detalle[$do_venta_detalle -> detalle_id]['tipo_nombre'] = $do_venta_detalle -> tipo_nombre;
			$detalle[$do_venta_detalle -> detalle_id]['cat_id'] = $do_venta_detalle -> cat_id;
			$detalle[$do_venta_detalle -> detalle_id]['cat_nombre'] = $do_venta_detalle -> cat_nombre;
			$detalle[$do_venta_detalle -> detalle_id]['prod_calibre'] = $do_venta_detalle -> detalle_prod_calibre;
			$detalle[$do_venta_detalle -> detalle_id]['prod_nombre'] = $do_venta_detalle -> prod_nombre;
			$detalle[$do_venta_detalle -> detalle_id]['prod_cant'] = $do_venta_detalle -> detalle_prod_cant - $do_venta_detalle -> detalle_cant_dev;
			$detalle[$do_venta_detalle -> detalle_id]['prod_val'] = ($do_venta_detalle -> detalle_prod_precio_u * $do_venta_detalle -> detalle_prod_cant - $do_venta_detalle -> detalle_descuento_parcial) / $do_venta_detalle -> detalle_prod_cant;
			$detalle[$do_venta_detalle -> detalle_id]['prod_desc'] = $do_venta_detalle -> detalle_descuento_parcial;
			$detalle[$do_venta_detalle -> detalle_id]['prod_tot'] = ($do_venta_detalle -> detalle_prod_precio_u * $do_venta_detalle -> detalle_prod_cant) - $do_venta_detalle -> detalle_descuento_parcial;
		}
	}

	if($do_venta_detalle -> N == 0){	//No se puede devolver mas productos
		return false;
	}
	// Checkeo si tengo boleto para dar la opcion descuento/NC
	$cobro = DB_DataObject::factory('cobro_cliente');
	$cobro -> cobro_venta_id = $venta_id;
	$cobro -> cobro_forma_pago = 5;
	$cobro -> find(true);

	if($cobro -> N) {
		$productos_dev['tiene_boleto_id'] = $cobro -> cobro_bono_id;
	} else {
		$productos_dev['tiene_boleto_id'] = false;
	}
	
	require_once('public/modales/nueva_devolucion.html');
	exit;
?>