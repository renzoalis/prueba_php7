<?php
	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');


	$fecha = $_POST['fecha_balanceDiario'];

	// $do_ventas = DB_DataObject::factory('venta');
	// $do_compras = DB_DataObject::factory('compra');
	// $do_stock = DB_DataObject::factory('venta');

	$xls = new Spreadsheet_Excel_Writer();
	$xls->send('balance_diario_'.date("d/m/Y").'.xls');



	$xls->close();

	exit;
?>
