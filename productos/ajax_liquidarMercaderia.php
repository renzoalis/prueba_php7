<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_ps = DB_DataObject::factory('producto_stock');
	$liquidar = $do_ps -> liquidarMercaderia($_POST['id']);

	echo $liquidar;
	exit;
?>