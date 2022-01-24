<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	// busco primero el id de la compra con el id del costo de mercaderia
    $do_costo_mercaderia = DB_DataObject::factory('costo_mercaderia');
    $do_costo_mercaderia -> whereAdd('cm_id = '.$_POST['id']);
    $do_costo_mercaderia -> find(true);
    $id_compra = $do_costo_mercaderia -> cm_compra_id;
    // una vez que tengo el id filtro por ese

	$do_compras = DB_DataObject::factory('compra');
	$do_compras -> whereAdd('compra_id = '.$id_compra);
	$do_proveedor = DB_DataObject::factory('proveedor');
	$do_transportista = DB_DataObject::factory('transportista');

	$do_compras -> joinAdd($do_proveedor);
	$do_compras -> joinAdd($do_transportista,"LEFT");

	$do_compras -> find(true);

	$do_pagos_prov = DB_DataObject::factory('pago_proveedor');
	$do_pagos_prov -> whereAdd('pago_prov_id = '.$do_compras -> compra_prov_id.' or pago_prov_id = 9999 or pago_prov_id = 9998');
	$do_pagos_prov -> pago_compra_id = $do_compras -> compra_id;
	$do_pagos_prov -> find();
	
	$respuesta = array();

	$respuesta['compra'] = $do_compras;
	

	// Traigo detalle de la compra
	$do_compras_detalle = DB_DataObject::factory('compra_detalle');
	$do_compras_detalle -> whereAdd('detalle_compra_id = '.$do_compras -> compra_id);
	
	// DB_DataObject::debugLevel(1);
	$do_producto = DB_DataObject::factory('producto');
	
	$do_tipo = DB_DataObject::factory('tipo');
	$do_categoria = DB_DataObject::factory('categoria');
	
	$do_categoria -> joinAdd($do_tipo);
	$do_producto -> joinAdd($do_categoria);

	$do_compras_detalle -> joinAdd($do_producto);
	
	$do_compras_detalle -> find(); 
	// print_r($do_compras_detalle);exit;
	$detalle = array();
	while ($do_compras_detalle -> fetch()) { 
		// print_r($do_compras_detalle);exit;
		$detalle[$do_compras_detalle -> detalle_id]['cat_nombre'] = $do_compras_detalle -> cat_nombre;
		$detalle[$do_compras_detalle -> detalle_id]['tipo_nombre'] = $do_compras_detalle -> tipo_desc;
		$detalle[$do_compras_detalle -> detalle_id]['prod_id'] = $do_compras_detalle -> prod_id;  
		$detalle[$do_compras_detalle -> detalle_id]['prod_modelo'] = $do_compras_detalle -> prod_nombre.' ('.$do_compras_detalle -> prod_alias.')'; 
		$detalle[$do_compras_detalle -> detalle_id]['calibre'] = $do_compras_detalle -> detalle_prod_calibre; 
		$detalle[$do_compras_detalle -> detalle_id]['cant'] = $do_compras_detalle -> detalle_prod_cant; 
		$detalle[$do_compras_detalle -> detalle_id]['precio_por_kg'] = $do_compras_detalle -> detalle_prod_precio_u; 
		$detalle[$do_compras_detalle -> detalle_id]['tipo'] = $do_compras_detalle -> ct_id;
		$detalle[$do_compras_detalle -> detalle_id]['precio_parcial'] = $do_compras_detalle -> detalle_prod_cant * $do_compras_detalle -> detalle_prod_precio_u;
	}


	/* EDITAR - QUEDA COMENTADO POR LAS DUDAS*/

		$do_prov = DB_DataObject::factory('proveedor');
		$do_prov -> find();

		$proveedores = array();

		while ($do_prov -> fetch()) { 
			$proveedores[$do_prov -> prov_id]['id'] = $do_prov -> prov_id;
			$proveedores[$do_prov -> prov_id]['nombre'] = $do_prov -> prov_nombre;
		}

		$do_transp = DB_DataObject::factory('transportista');
		$do_transp -> find();

		$transportista = array();

		while ($do_transp -> fetch()) { 
			$transportista[$do_transp -> transportista_id]['id'] = $do_transp -> transportista_id;
			$transportista[$do_transp -> transportista_id]['nombre'] = $do_transp -> transportista_nombre;
		}

	$do_conceptos = DB_DataObject::factory('compra_concepto');
	$do_conceptos -> whereAdd('cc_compra_id = '.$id_compra);
	$do_conceptos -> find();

	$do_conceptos2 = DB_DataObject::factory('compra_concepto');
	$do_conceptos2 -> whereAdd('cc_compra_id = '.$id_compra);
	$do_conceptos2 -> find();

	// si ya cargo algun concepto de carga o de flete, oculto los botones 
	$ocultarbotondescarga = 1;
	$ocultarbotonflete = 1;
	$ocultarbotoncostomercaderia = 1;

		while ($do_conceptos2 -> fetch()) { 
			if ($do_conceptos2 -> cc_tipo == 1) // descarga
			{$ocultarbotondescarga = 0;}
		   if ($do_conceptos2 -> cc_tipo == 3) // flete
			{$ocultarbotonflete = 0;}
			if ($do_conceptos2 -> cc_tipo == 13) // costo mercaderia
			{$ocultarbotoncostomercaderia = 0;}
		}

	// print_r($ocultarbotoncostomercaderia);exit;
	$caja = DB_DataObject::factory('caja');
	$cajaAbierta = $caja -> cajaAbiertaHoy();
	
	require_once('public/modales/edit_compra.html');
	exit;
?>