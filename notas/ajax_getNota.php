<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_nota = DB_DataObject::factory('notas');
	$do_nota -> whereAdd('nota_id = '.$_POST['id']);

	$do_cliente = DB_DataObject::factory('cliente');
	$do_usuario = DB_DataObject::factory('usuario');

	$do_nota -> joinAdd($do_usuario,"LEFT");
	$do_nota -> joinAdd($do_cliente,"LEFT");

	$do_nota -> find(true);
	
	// Para chequear si es nota por devolución de mercaderia y en que venta
	$esdevolucion = 0;
	list($tipo, $idVenta) = split('[/:]', $do_nota -> nota_observacion);
	if ($tipo == "Devolucion de mercaderia en venta"){
		$esdevolucion =1;
	}

	$clientes = array();
	$clientes['nombre'] = $do_nota -> cliente_nombre;

	$do_cli = DB_DataObject::factory('cliente');
	$do_cli -> cliente_baja = 0;
	$do_cli -> find();

	$clientes = array();

	while ($do_cli -> fetch()) { 
		$clientes[$do_cli -> cliente_id]['id'] = $do_cli -> cliente_id;
		$clientes[$do_cli -> cliente_id]['nombre'] = $do_cli -> cliente_nombre;
	}

	require_once('public/modales/ver_nota.html');
	exit;
?>