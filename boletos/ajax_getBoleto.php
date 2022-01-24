<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');


	$boleto = DB_DataObject::factory('boleto');

	$do_banco = DB_DataObject::factory('banco');
    $do_cliente = DB_DataObject::factory('cliente');
    $do_estado = DB_DataObject::factory('boleto_estado');

    $boleto -> joinAdd($do_cliente);
    $boleto -> joinAdd($do_banco);
    $boleto -> joinAdd($do_estado);
    
	$boleto -> whereAdd('boleto_id = '.$_POST['id']);   

	$boleto -> find(true);

	require_once('public/edit_boleto.html');
	exit;
?>