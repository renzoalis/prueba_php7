<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_ventas = DB_DataObject::factory('venta');
	$do_ventas -> whereAdd('venta_id = '.$_POST['id']);
	$do_cliente = DB_DataObject::factory('cliente');
	$do_usuario = DB_DataObject::factory('usuario');

	$do_ventas -> joinAdd($do_cliente,"LEFT");
	$do_ventas -> joinAdd($do_usuario,"LEFT");

	$do_ventas -> find(true);

	$clientes = array();
	$clientes['nombre'] = $do_ventas -> cliente_nombre;
	$clientes['id'] = $do_ventas -> cliente_id;

	$do_cliente -> cliente_id = $do_ventas -> cliente_id;
	$do_cliente -> find(true);

	$saldo['valor'] = $do_cliente -> getSaldoValor();
	if($saldo['valor'] >= 0){
		$saldo['clase'] = 'cc_verde';
		$saldo['texto'] = 'Sin deuda $'.$saldo['valor'];
	}else{
		$saldo['clase'] = 'cc_rojo';
		$saldo['texto'] = 'Con deuda $'.$saldo['valor'];		
	}
	// print_R($saldo);exit;
	// $do_ventas -> venta_fh = date('d/m/Y', strtotime($do_ventas -> venta_fh));

	$respuesta = array();

	$respuesta['venta'] = $do_ventas;

	// Traigo detalle de la venta
	$do_venta_detalle = DB_DataObject::factory('venta_detalle');
	$do_venta_detalle -> whereAdd('detalle_venta_id = '.$do_ventas -> venta_id);
	
	$do_producto = DB_DataObject::factory('producto');
	$do_tipo = DB_DataObject::factory('tipo');
	$do_categoria = DB_DataObject::factory('categoria');
	
	$do_categoria -> joinAdd($do_tipo,"LEFT");
	$do_producto -> joinAdd($do_categoria,"LEFT");
	$do_venta_detalle -> joinAdd($do_producto,"LEFT");
	
	$do_venta_detalle -> find(); 

	while ($do_venta_detalle -> fetch()) { 
		 
		$detalle[$do_venta_detalle -> detalle_id]['prod_id'] = $do_venta_detalle -> detalle_prod_id;
		$detalle[$do_venta_detalle -> detalle_id]['tipo_id'] = $do_venta_detalle -> tipo_id;
		$detalle[$do_venta_detalle -> detalle_id]['tipo_nombre'] = $do_venta_detalle -> tipo_nombre;
		$detalle[$do_venta_detalle -> detalle_id]['cat_id'] = $do_venta_detalle -> cat_id;
		$detalle[$do_venta_detalle -> detalle_id]['cat_nombre'] = $do_venta_detalle -> cat_nombre;
		$detalle[$do_venta_detalle -> detalle_id]['prod_nombre'] = $do_venta_detalle -> prod_nombre;
		$detalle[$do_venta_detalle -> detalle_id]['prod_calibre'] = $do_venta_detalle -> detalle_prod_calibre;
		$prod = DB_DataObject::factory('producto');
        $prod -> prod_id = $do_venta_detalle -> detalle_prod_id;
        $max = $prod -> getStock($do_venta_detalle -> detalle_prod_calibre) + $do_venta_detalle -> detalle_prod_cant;
        $detalle[$do_venta_detalle -> detalle_id]['prod_max'] = $max;
		$detalle[$do_venta_detalle -> detalle_id]['prod_lote'] = $do_venta_detalle -> detalle_prod_lote;
		$detalle[$do_venta_detalle -> detalle_id]['prod_cant'] = $do_venta_detalle -> detalle_prod_cant;
		$detalle[$do_venta_detalle -> detalle_id]['prod_val'] = $do_venta_detalle -> detalle_prod_precio_u;
		$detalle[$do_venta_detalle -> detalle_id]['prod_desc'] = $do_venta_detalle -> detalle_descuento_parcial;
		$detalle[$do_venta_detalle -> detalle_id]['prod_tot'] = ($do_venta_detalle -> detalle_prod_precio_u * $do_venta_detalle -> detalle_prod_cant) - $do_venta_detalle -> detalle_descuento_parcial;
		$detalle[$do_venta_detalle -> detalle_id]['prod_tot_real'] = ($do_venta_detalle -> detalle_prod_precio_u * $do_venta_detalle -> detalle_prod_cant);

		// $detalle[$do_venta_detalle -> detalle_id]['pord_tot'] = $do_venta_detalle -> detalle_prod_precio_u * $do_venta_detalle -> detalle_prod_cant;
		// $detalle[$do_ventas_detalle -> detalle_id]['detalle_prod_peso'] = $do_ventas_detalle -> detalle_prod_peso;
		// $detalle[$do_ventas_detalle -> detalle_id]['detalle_precio_parcial'] = $do_ventas_detalle -> detalle_prod_peso * $do_ventas_detalle -> detalle_prod_precio_u;
	}

	$do_conceptos = DB_DataObject::factory('venta_concepto');
	$do_conceptos -> whereAdd('vc_venta_id = '.$_POST['id']);
	$do_conceptos -> find();

	$caja = DB_DataObject::factory('caja');
    $cajaAbierta = $caja -> cajaAbiertaHoy();

     // clientes para editar 
    $clientes_edit = array();
	$do_cli_edit = DB_DataObject::factory('cliente');
	$do_cli_edit -> cliente_baja = 0;
    $do_cli_edit -> orderBy('cliente_nombre ASC');
    $do_cli_edit -> find();

    while ($do_cli_edit -> fetch()) { 
        $clientes_edit[$do_cli_edit  -> cliente_id]['id'] = $do_cli_edit  -> cliente_id;
        $clientes_edit[$do_cli_edit  -> cliente_id]['nombre'] = $do_cli_edit  -> cliente_nombre;
    }

	require_once('public/modales/edit_venta_pendiente.html');
	exit;
?>