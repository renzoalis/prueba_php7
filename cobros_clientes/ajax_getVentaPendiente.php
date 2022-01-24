<?php
 
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');


	$do_venta = DB_DataObject::factory('venta');

    $do_venta -> whereAdd('venta_cliente_id ='.$_POST['id'].' AND venta_estado_id = 1');
    $do_venta -> find();

    echo json_encode($do_venta -> N);
?>