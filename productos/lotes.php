<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$producto_stock = DB_DataObject::factory('producto_stock');
	$producto_stock -> getPPVLiquidado();

	if($_GET['id_prod']) { 
		$producto_stock = DB_DataObject::factory('producto_stock');
		$listado = $producto_stock -> getListado($_GET['id_prod']);
	}

	$producto = DB_DataObject::factory('producto');
	$productos = $producto -> getProductosConStock();


	require_once('public/listado_lotes.html');
	exit;
?>