<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$prem = $_POST['premium'];

	$producto = DB_DataObject::factory('producto');
	$do_productos = $producto -> getProductos($_POST['id']);
	$do_stock = $do_productos -> getStockPorCalibre();

	require_once('public/modales/edit_producto.html');
	exit;
?>