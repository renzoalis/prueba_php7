<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_nota = DB_DataObject::factory('notas');
	$do_nota -> whereAdd('nota_id = '.$_POST['id']);

	$do_exportador = DB_DataObject::factory('exportador');
	
	$do_nota -> joinAdd($do_exportador);

	$do_nota -> find(true);
    //print_r($do_nota);exit;

	if($do_nota -> nota_ccop_tipo == 5){
		$tipo_nota = "NC";
		$tipo_select = "NOTA CREDITO";
	}elseif($do_nota -> nota_ccop_tipo == 6){
		$tipo_nota = "ND";
		$tipo_select = "NOTA DEBITO";
	}


	require_once('public/modales/ver_nota.html');
	exit;
?>