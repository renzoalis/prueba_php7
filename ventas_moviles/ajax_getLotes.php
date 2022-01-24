<?php
	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');
	$lote_nombre = $_POST['lote_nombre'];
	$ps = DB_DataObject::factory('producto_stock');
	$lotes = $ps -> getLotes($_POST['prod_id'],$_POST['ps_calibre']); 
// --------------------------------						Template								------------------------------------ //

	require_once('public/ajax_seleccionarLote.html');
	exit;
?>