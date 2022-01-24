<?php
 
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_cli = DB_DataObject::factory('cliente');
	$do_cli -> cliente_id = $_POST['id'];
	$do_cli -> find(true);
	
	print_r(json_encode($do_cli));

?>