<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	if($_POST['nueva_nota']) {
		$notas = DB_DataObject::factory('notas');
		$id = $notas -> nuevaNotaAdmin($_POST);
		header("Location: index.php?id_nota=".$id); 
	}

	$do_cli = DB_DataObject::factory('cliente');
	$do_cli -> cliente_baja = 0;
	$do_cli -> find();

	$clientes = array();

	while ($do_cli -> fetch()) { 
		$clientes[$do_cli -> cliente_id]['id'] = $do_cli -> cliente_id;
		$clientes[$do_cli -> cliente_id]['nombre'] = $do_cli -> cliente_nombre;
	}

	$do_prov = DB_DataObject::factory('proveedor');
	$do_prov -> prov_baja = 0;
	$do_prov -> find();

	$proveedores = array();

	while ($do_prov -> fetch()) { 
		$proveedores[$do_prov -> prov_id]['id'] = $do_prov -> prov_id;
		$proveedores[$do_prov -> prov_id]['nombre'] = $do_prov -> prov_nombre;
	}

	$notas_agentes = DB_DataObject::factory('notas_tipo_agente');
	$notas_agentes -> whereAdd('ta_baja = 0');
	$notas_agentes -> find();

	$caja = DB_DataObject::factory('caja');
	$cajaAbierta = $caja -> cajaAbiertaHoy();

	$usuario =  DB_DataObject::factory('usuario');
	$esAdmin = $usuario -> esAdmin();

	require_once('public/index.html');
	exit;
?>
