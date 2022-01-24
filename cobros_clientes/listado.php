<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$caja = DB_DataObject::factory('caja');
	$ultima_caja = $caja -> getUltimaCaja();
	$f_desde = $ultima_caja -> caja_fh_inicio;
	$f_hasta = date('Y-m-d 23:59:59');
	$cobro_cliente = DB_DataObject::factory('cobro_cliente');

	if(!$_GET['fecha_desde']){
		$do_cobros = $cobro_cliente -> getCobros($f_desde,date('Y-m-d'));
		$campoFecha = date('d/m/Y',strtotime($f_desde)).' - '.date('d/m/Y');
	} else {
		$do_cobros = $cobro_cliente -> getCobros($_GET['fecha_desde'],$_GET['fecha_hasta']);
		$campoFecha = date('d/m/Y',strtotime($_GET['fecha_desde'])).' - '.date('d/m/Y',strtotime($_GET['fecha_hasta']));
	}

	if($_POST['nuevo_cobro']) {
		$cobro = DB_DataObject::factory('cobro_cliente');
		$id = $cobro -> nuevoCobro($_POST);
		header("Location: listado.php?id_cobro=".$id); 
	}

	$do_cli = DB_DataObject::factory('cliente');
	$do_cli -> whereAdd('cliente_baja = 0 AND cliente_id != 9999');
	$do_cli -> orderBy('cliente_nombre ASC');
	$do_cli -> find();

	$do_banco = DB_DataObject::factory('banco');
	$do_banco -> banco_baja = 0;
	$do_banco -> find();

	$do_banco2 = DB_DataObject::factory('banco');
	$do_banco2 -> banco_baja = 0;
	$do_banco2 -> find();

	$do_banco_et = DB_DataObject::factory('banco');
	$do_banco_et -> banco_baja = 0;
	$do_banco_et -> find();

	$do_banco_rt = DB_DataObject::factory('banco');
	$do_banco_rt -> banco_baja = 0;
	$do_banco_rt -> find();

	$do_banco_d = DB_DataObject::factory('banco');
	$do_banco_d -> banco_baja = 0;
	$do_banco_d -> find();

	$clientes = array();

	while ($do_cli -> fetch()) { 
		$clientes[$do_cli -> cliente_id]['id'] = $do_cli -> cliente_id;
		$clientes[$do_cli -> cliente_id]['nombre'] = $do_cli -> cliente_nombre;
	}

	$caja = DB_DataObject::factory('caja');
	$cajaAbierta = $caja -> cajaAbiertaHoy();
	
	// fin Nuevo Cobro
	require_once('public/listado_cobros.html');
	exit;
?>
