<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_nota = DB_DataObject::factory('notas');
	$do_nota -> whereAdd('nota_id = '.$_POST['id']);

	$do_proveedor = DB_DataObject::factory('proveedor');
	$do_usuario = DB_DataObject::factory('usuario');

	$do_nota -> joinAdd($do_usuario,"LEFT");
	$do_nota -> joinAdd($do_proveedor,"LEFT");

	$do_nota -> find(true);

	$proveedores = array();
	$proveedores['nombre'] = $do_nota -> prov_nombre;

	$do_prov = DB_DataObject::factory('proveedor');
	$do_prov -> proveedor_baja = 0;
	$do_prov -> find();

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