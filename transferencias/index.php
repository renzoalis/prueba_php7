<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	//print_r(ADM_PATH);exit;

	// Alta de transferencia
    if($_POST['nueva_transferencia']) {
    	//print_R($_POST);exit;
		$transferencias = DB_DataObject::factory('transferencias');
		$resp = $transferencias -> agregarTransferencia($_POST);
		if($resp == 'error-stock'){
			header("Location: index.php?error=error-stock");  
		}else{
			// Si no conecta, conexion devuelve "error-conexion", sino el ID de la transf en el sistema matriz
			header('Location: index.php?id_nuevo='.$resp['id_local'].'&conexion='.$resp['id_matriz']);  
		}
	}

	// Guardar datos de transferencia recibida
	if($_POST['recibi_transferencia']) {
		//print_r($_POST);exit;
		$transferencias = DB_DataObject::factory('transferencias');
		$respuesta = $transferencias -> recibiTransferencia($_POST);
		
		if($respuesta['estado'] == '500'){
			header("Location: index.php?conexion=error-conexion&recibida=ok");  
		}elseif($respuesta['estado'] == '200'){
			header("Location: index.php?recibida=ok&conexion_recibida=ok");  
		}
	}


	if($_POST['nueva_diferencia']) {
		// print_r($_POST);exit;
		$transferencias = DB_DataObject::factory('transferencias');
		$resp = $transferencias -> diferenciaMercaderia($_POST);
		
		header('Location: index.php?id_nuevo='.$resp['id_local'].'&conexion='.$resp['id_matriz']);  
		
	}

	// Datos del puesto
	$do_puesto = DB_DataObject::factory('puesto');
	$do_puesto -> whereAdd('puesto_id != '.PUESTO_ID);
	$do_puesto -> find();

	// Listado de productos
	$do_prod = DB_DataObject::factory('producto');
	$do_prod_stock = DB_DataObject::factory('producto_stock');

	$listado_productos = $do_prod -> getProductosTransf();
	$productos = array();
	

	// print_r(($listado_productos));exit;
	while ($listado_productos -> fetch()) { 
			$id = $listado_productos -> prod_id;
			$modelo = $listado_productos -> tipo_nombre.' | '.$listado_productos -> cat_nombre.' | '. $listado_productos -> prod_nombre.' ('.$listado_productos -> prod_alias.')';
			$calibre = $listado_productos -> ps_calibre;
			$lote = $listado_productos -> ps_id;
			$stock = $listado_productos -> ps_cantidad;
			
			$productos[$listado_productos -> prod_id.'-'.$calibre][$lote]['modelo'] = $modelo.' | calibre: '.$calibre.' | Lote: '.$lote.' | Stock: '.$stock;
			$productos[$listado_productos -> prod_id.'-'.$calibre][$lote]['id'] = $id;
			$productos[$listado_productos -> prod_id.'-'.$calibre][$lote]['calibre'] = $calibre;
			$productos[$listado_productos -> prod_id.'-'.$calibre][$lote]['stock'] = $stock;
			$productos[$listado_productos -> prod_id.'-'.$calibre][$lote]['costou'] = $do_prod_stock -> getCostouXLote($lote);
	}

	//TRANSPORTISTAS
	$do_transp = DB_DataObject::factory('transportista');
	$do_transp -> transportista_baja = 0;
	$do_transp -> find();

	$caja = DB_DataObject::factory('caja');
	$cajaAbierta = $caja -> cajaAbiertaHoy();

	require_once('public/listado_transferencias.html');
	exit;
?>
