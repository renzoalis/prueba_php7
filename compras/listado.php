<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');
	
	// print_r($_POST);
	 
	if($_POST['input_observacion_compra']) {
		$compra = DB_DataObject::factory('compra');
		$id = $compra -> actualizarObservacion($_POST);
		header("Location: listado.php?id_compra_abrir=".$id); 
	}

	if($_POST['input_id_transp_e']) {
		$compra = DB_DataObject::factory('compra');
		$id = $compra -> agregarTransportista($_POST);
		header("Location: listado.php?id_compra_abrir=".$id); 
	}

	if($_POST['nueva_compra']) {
		$compra = DB_DataObject::factory('compra');
		$id = $compra -> nuevaCompra($_POST);
		header("Location: listado.php?id_compra=".$id); 
	}

	if($_POST['nuevo_pago']) {
		$pago = DB_DataObject::factory('pago_proveedor');
		$id = $pago -> nuevoPago($_POST);

		$c = DB_DataObject::factory('compra');
		$c -> compra_id = $_POST['compra_id'];
		$c -> find(true);
		$c -> compra_estado_id = 2;
	
		$c -> update();
		header("Location: listado.php?id_pago=".$id."&id_compra_abrir=".$_POST['compra_id']);
	}


	if($_POST['nuevo_concepto']) {
		//print_r($_POST);exit;
		$concepto = DB_DataObject::factory('compra_concepto');
		$id = $concepto -> nuevoConcepto($_POST);

		header("Location: listado.php?id_concepto=".$id."&id_compra_abrir=".$_POST['concepto_compra_id']);
	}

	$do_prod = DB_DataObject::factory('producto');

	$listado_productos = $do_prod -> getproductos();
	$productos = array();

	while ($listado_productos -> fetch()) { 
		$productos[$listado_productos -> prod_id]['id'] = $listado_productos -> prod_id;
		$productos[$listado_productos -> prod_id]['modelo'] = $listado_productos -> tipo_nombre.' | '.$listado_productos -> cat_nombre.' | '. $listado_productos -> prod_nombre.' ('.$listado_productos -> prod_alias.')';
	}

	$do_cate = DB_DataObject::factory('categoria');
	$do_categorias = $do_cate -> getCategorias();
	$do_categorias_edit = $do_cate -> getCategorias();
	//TRANSPORTISTAS
	$do_transp = DB_DataObject::factory('transportista');
	$do_transp -> transportista_baja = 0;
	$do_transp -> find();

	$transportista = array();

	while ($do_transp -> fetch()) { 
		$transportista[$do_transp -> transportista_id]['id'] = $do_transp -> transportista_id;
		$transportista[$do_transp -> transportista_id]['nombre'] = $do_transp -> transportista_nombre;
	}
	//TRANSPORTISTAS
	//PROVEEDORES
	$do_prov = DB_DataObject::factory('proveedor');
	$do_prov -> prov_baja = 0;
	$do_prov -> find();

	$proveedores = array();

	while ($do_prov -> fetch()) { 
		$proveedores[$do_prov -> prov_id]['id'] = $do_prov -> prov_id;
		$proveedores[$do_prov -> prov_id]['nombre'] = $do_prov -> prov_nombre;
	}
	//PROVEEDORES


	$do_prov = DB_DataObject::factory('proveedor');
	$do_prov -> prov_baja = 0;
	$do_prov -> find();

	$do_banco = DB_DataObject::factory('banco');
	$do_banco -> banco_baja = 0;
	$do_banco -> find();

	$do_banco_et = DB_DataObject::factory('banco');
	$do_banco_et -> banco_baja = 0;
	$do_banco_et -> find();

	$do_banco_rt = DB_DataObject::factory('banco');
	$do_banco_rt -> banco_baja = 0;
	$do_banco_rt -> find();

	$do_banco_d = DB_DataObject::factory('banco');
	$do_banco_d -> banco_baja = 0;
	$do_banco_d -> find();


	$do_banco_ch3 = DB_DataObject::factory('banco');
	$do_banco_ch3 -> banco_baja = 0;
	$do_banco_ch3 -> find();

	$do_cheques_terceros = DB_DataObject::factory('cheque');
	$do_cheques_terceros -> joinAdd($do_banco_ch3);
	$do_cheques_terceros -> whereAdd('cheque_estado = 1');	// Pendiente de cobro o vencido
	$do_cheques_terceros -> find();

	$compra = DB_DataObject::factory('compra');

	$caja = DB_DataObject::factory('caja');
	$cajaAbierta = $caja -> cajaAbiertaHoy();
	
	if($_GET['fecha_desde']){
		$do_compras = $compra -> getCompras($_GET['fecha_desde'],$_GET['fecha_hasta']);
		$campoFecha = date('d/m/Y',strtotime($_GET['fecha_desde'])).' - '.date('d/m/Y',strtotime($_GET['fecha_hasta']));
	}else{
		$ultima_caja = $caja -> getUltimaCaja();
		$f_desde = $ultima_caja -> caja_fh_inicio;
		$f_hasta = date('Y-m-d 23:59:59');

		$do_compras = $compra -> getCompras($f_desde,date('Y-m-d H:i:s'));
		$campoFecha = date('d/m/Y',strtotime($f_desde)).' - '.date('d/m/Y');
	}

	$do_concepto = DB_DataObject::factory('compra_concepto_tipo');
	$do_concepto -> whereAdd('cc_tipo_baja = 0');
	$do_concepto -> find();


	require_once('public/listado_compras.html');
	exit;
?>
