<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	// $do_producto = DB_DataObject::factory('producto');
	// $do_producto -> prod_id = $_POST['id'];
	// if($do_producto -> find(true)){
	// 	echo json_encode($do_producto -> getStock($_POST['calibre']));
	// }else{
	// 	echo json_encode(0);
	// }
	$do_producto = DB_DataObject::factory('producto_stock');
	$do_producto -> ps_id = $_POST['lote'];
	$do_producto -> find(true);

	echo json_encode($do_producto -> ps_cantidad);

	exit;
?>