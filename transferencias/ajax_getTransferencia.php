<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_transferencias = DB_DataObject::factory('transferencias');
	$do_transferencias -> whereAdd('transf_id = '.$_POST['id']);

	$do_transferencias -> find(true);
	

	// Traigo detalle de la transferencia
	$do_transferencia_detalle = DB_DataObject::factory('transferencias_detalle');
	$do_transferencia_detalle -> whereAdd('detalle_transferencia_id = '.$do_transferencias -> transf_id);
	
	// DB_DataObject::debugLevel(1);
	$do_producto = DB_DataObject::factory('producto');
	
	$do_tipo = DB_DataObject::factory('tipo');
	$do_categoria = DB_DataObject::factory('categoria');
	
	$do_categoria -> joinAdd($do_tipo);
	$do_producto -> joinAdd($do_categoria);

	$do_transferencia_detalle -> joinAdd($do_producto);
	
	$do_transferencia_detalle-> find(); 
	
	// print_r($do_compras_detalle);exit;
	$detalle = array();
	while ($do_transferencia_detalle -> fetch()) { 
		// print_r($do_transferencia_detalle);exit;
		$detalle[$do_transferencia_detalle -> detalle_id]['cat_nombre'] = $do_transferencia_detalle -> cat_nombre;
		$detalle[$do_transferencia_detalle -> detalle_id]['tipo_nombre'] = $do_transferencia_detalle -> tipo_desc;
		$detalle[$do_transferencia_detalle -> detalle_id]['prod_id'] = $do_transferencia_detalle -> prod_id;  
		$detalle[$do_transferencia_detalle -> detalle_id]['prod_modelo'] = $do_transferencia_detalle -> prod_nombre.' ('.$do_transferencia_detalle -> prod_alias.')';
		$detalle[$do_transferencia_detalle -> detalle_id]['detalle_calibre'] = $do_transferencia_detalle -> detalle_calibre; 
		$detalle[$do_transferencia_detalle -> detalle_id]['cantidad'] = $do_transferencia_detalle -> detalle_producto_cantidad_origen; 
		$detalle[$do_transferencia_detalle-> detalle_id]['costo_carga'] = $do_transferencia_detalle-> detalle_costo_carga; 
		// $detalle[$do_transferencia_detalle-> detalle_id]['costo_flete'] = $do_transferencia_detalle-> detalle_costo_flete_origen; 
		$detalle[$do_transferencia_detalle-> detalle_id]['costo_flete_destino'] = $do_transferencia_detalle-> detalle_costo_flete_destino; 
		$detalle[$do_transferencia_detalle-> detalle_id]['costo_descarga'] = $do_transferencia_detalle-> detalle_costo_descarga; 
		$detalle[$do_transferencia_detalle-> detalle_id]['ppv'] = $do_transferencia_detalle-> detalle_ppv; 
		$detalle[$do_transferencia_detalle-> detalle_id]['total_costo_carga_parcial'] = $do_transferencia_detalle-> detalle_costo_carga * $do_transferencia_detalle -> detalle_producto_cantidad_origen; 
		$detalle[$do_transferencia_detalle-> detalle_id]['observacion_parcial'] = $do_transferencia_detalle-> detalle_observacion; 
		$detalle[$do_transferencia_detalle-> detalle_id]['cantidad_recibida'] = $do_transferencia_detalle-> detalle_producto_cantidad_destino; 
		$detalle[$do_transferencia_detalle-> detalle_id]['detalle_costo_unitario'] = $do_transferencia_detalle-> detalle_costo_unitario; 
		$detalle[$do_transferencia_detalle-> detalle_id]['detalle_lote'] = $do_transferencia_detalle-> detalle_lote; 
		$detalle[$do_transferencia_detalle-> detalle_id]['detalle_lote_desc'] = $do_transferencia_detalle-> detalle_lote_desc; 
		$detalle[$do_transferencia_detalle-> detalle_id]['total'] = $do_transferencia_detalle-> detalle_producto_cantidad_destino * $do_transferencia_detalle-> detalle_costo_descarga + $do_transferencia_detalle-> detalle_producto_cantidad_destino * $do_transferencia_detalle-> detalle_costo_flete_destino; 

	}


	$do_diferencia_mercaderia = DB_DataObject::factory('diferencia_mercaderia');
	$do_diferencia_mercaderia -> dif_transferencia_id = $do_transferencias -> transf_id;
	$do_diferencia_mercaderia -> find();
	// print_R($do_diferencia_mercaderia);exit;

	//Traigo el listado de transportistas para el select
	$do_transp = DB_DataObject::factory('transportista');
		$do_transp -> transportista_baja = 0;
		$do_transp -> find();
		$transportista = array();
		while ($do_transp -> fetch()) { 
			$transportista[$do_transp -> transportista_id]['id'] = $do_transp -> transportista_id;
			$transportista[$do_transp -> transportista_id]['nombre'] = $do_transp -> transportista_nombre;
		}

	if ($do_transferencias -> transf_tipo == 1) {      //Transferencia enviada
		require_once('public/modales/edit_transferencia_enviada.html');
	} else {  //Transferencia REcibida
		$caja = DB_DataObject::factory('caja');
		$cajaAbierta = $caja -> cajaAbiertaHoy();
		require_once('public/modales/edit_transferencia_recibida.html');
	}

	exit;
?>