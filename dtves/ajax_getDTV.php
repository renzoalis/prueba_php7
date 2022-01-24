<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$prem = $_POST['premium'];

	$do_dtv = DB_DataObject::factory('dtv');
	$do_dtv -> dtv_id = $_POST['id'];
	$do_dtv -> find(true);

	$productos_dtv = DB_DataObject::factory('dtv_producto');
	$productos_dtv -> dtv_id = $_POST['id'];
	$productos_dtv -> find();

	require_once('public/modales/edit_dtv.html');
	exit;
?>