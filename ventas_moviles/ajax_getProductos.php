<?php
	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$productos = DB_DataObject::factory('producto_stock');
	$producto = DB_DataObject::factory('producto');
	$productos ->whereAdd('ps_cantidad > 0'); 
   	$productos -> joinAdd($producto);
    $productos ->whereAdd('prod_cat_id ='.$_POST['cat_id']);

	$productos -> find();

	$tipo_cat_nombre = $_POST['tipo_cat_nombre'];
// --------------------------------						Template								------------------------------------ //

	require_once('public/ajax_seleccionarProducto.html');
	exit;
?>