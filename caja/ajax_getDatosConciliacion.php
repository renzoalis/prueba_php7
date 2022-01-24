<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$usr = DB_DataObject::factory('usuario');
	$usr -> usua_id = $_SESSION['usuario']['id'];
	$usr -> find(true);

	$do_caja = DB_DataObject::factory('caja');
	$do_caja -> caja_id = $_POST['id'];
	$do_caja -> find(true);

	$ps = DB_DataObject::factory('producto_stock');
	$lotes = $ps -> getLotesConciliacion($do_caja -> caja_fh_inicio);

	$tipos_conc = DB_DataObject::factory('conciliacion_tipo');
	$tipos_conciliacion = $tipos_conc -> getTipos();

	require_once('public/modales/conciliarStock.html');

	exit;
?>