<?php
	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$calibre_nombre = $_POST['calibre_nombre'];
	$ps = DB_DataObject::factory('producto_stock');
	$calibres = $ps -> getCalibresVentaFisico($_POST['prod_id']); 
	if(sizeof($calibres) == 1 && $calibres['S/C']){  //Este producto no tiene calibre
		$producto = DB_DataObject::factory('producto');
		$producto -> prod_id = $_POST['prod_id'];
		$producto ->find(true);

		echo intval($producto -> getStock());
		exit;
	}


// --------------------------------						Template								------------------------------------ //

	require_once('public/ajax_seleccionarCalibre.html');
	exit;
?>