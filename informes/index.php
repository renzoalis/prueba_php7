<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	/* CC Clientes */
	$fecha_actual = new DateTime();	
	$f_desde =  $fecha_actual -> modify("-1 week");
	$campoFecha = date_format($f_desde,'d/m/Y').' - '.date('d/m/Y');
	$campoDiaDeHoy = date('d/m/Y').' - '.date('d/m/Y');


	$do_cli = DB_DataObject::factory('cliente');
	$do_cli -> cliente_baja = 0;
	$do_cli -> find();

	$clientes = array();

	while ($do_cli -> fetch()) { 
		$clientes[$do_cli -> cliente_id]['id'] = $do_cli -> cliente_id;
		$clientes[$do_cli -> cliente_id]['nombre'] = $do_cli -> cliente_nombre;
	}

	/* /CC Clientes */

	require_once('public/index.html');
	exit;
?>
