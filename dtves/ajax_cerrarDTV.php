<?php
 
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');


	   	$dtv = DB_DataObject::factory('dtv');
        $dtv -> dtv_id = $_POST['id'];
        $dtv -> find(true);
        $dtv -> dtv_estado = 2; // cerrado
        $dtv -> update();

?>