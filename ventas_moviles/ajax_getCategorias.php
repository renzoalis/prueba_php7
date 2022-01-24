<?php
	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_categoria = DB_DataObject::factory('categoria');
	$categorias = $do_categoria -> catConStock($_POST['tipo_id']);

	$_SESSION['unSoloTipo'] = $_POST['unSoloTipo'];
	$tipo_nombre = $_POST['tipo_nombre'];
// --------------------------------						Template								------------------------------------ //
	require_once('public/ajax_seleccionarCategoria.html');
	exit;
?>