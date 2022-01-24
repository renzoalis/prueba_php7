<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_caja = DB_DataObject::factory('caja');
	$do_caja -> whereAdd('caja_estado = 2 AND caja_matriz_id IS NULL');
	$do_caja -> find();

	echo $do_caja -> N;

	exit;
?>