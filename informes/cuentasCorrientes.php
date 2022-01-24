<?php
	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	/* CLIENTE */
	$do_cliente = DB_DataObject::factory('cliente');
	$cliente = $do_cliente -> getClientes($cliente_id);

	/* CUENTA CORRIENTE */
	$do_cc = DB_DataObject::factory('cliente_cuenta_corriente');
	$cuenta_corriente = $do_cc -> getCuentasCorrientes();

	/* ARMO EXCEL */
	$workbook = new Spreadsheet_Excel_Writer();
	$workbook->setVersion(8);
	$workbook->send('cc_clientes.xls');

	$worksheet = $workbook->addWorksheet('Cuenta corriente');
	$worksheet->setInputEncoding('utf-8');


	/* ESTILOS */
	$worksheet->setColumn(0,7,20);
	$workbook->setCustomColor(12, 220, 229, 248);
	$workbook->setCustomColor(13, 231, 232, 236);
	
	$format_title =& $workbook->addFormat();
	$format_title->setSize(13);
	$format_title->setColor('black');
	$format_title->setFgColor(22);
	$format_title->setAlign('merge');

	$format_column =& $workbook->addFormat();
	$format_column->setBold();
	$format_column->setSize(11);
	$format_column->setColor('black');
	$format_column->setFgColor(12);

	$format_derecha =& $workbook->addFormat();
	$format_derecha->setAlign('right');

	/* TITULOS */
	$worksheet->write(0, 0, 'Cuentas corrientes al '.date('d/m/Y'),$format_title);
	$worksheet->write(0, 1, '',$format_title);
	$worksheet->write(0, 2, '',$format_title);

	/* COLUMNAS */
	$worksheet->write(2, 0, 'ID',$format_column);
    $worksheet->write(2, 1, 'Cliente',$format_column);
    $worksheet->write(2, 2, 'Saldo',$format_column);
    $worksheet->write(2, 2, '',$format_column);
    $row = 2;

	/* FILAS */
	$total_resta = 0;
	$total = 0;
	foreach ($cuenta_corriente as $cc) {
		$row++;
        $worksheet->write($row, 0, $cc['id']);
        $worksheet->write($row, 1, $cc['cliente']);
        if($cc['saldo'] < 0){
        	$worksheet->write($row, 3, number_format($cc['saldo'],2,',','.'),$format_derecha);
        	$total_resta += $cc['saldo'];
        } else {
			$total += $cc['saldo'];
        	$worksheet->write($row, 2, number_format($cc['saldo'],2,',','.'),$format_derecha);
        }
	}

	$row = $row + 2;
    $worksheet->write($row, 0, '');
    $worksheet->write($row, 1, 'TOTAL  ', $format_column);
    $worksheet->write($row, 2, ''.number_format($total,2,',','.'),$format_derecha);
    $worksheet->write($row, 3, ''.number_format($total_resta,2,',','.'),$format_derecha);

    ob_clean();	
	$workbook->close();

	exit;
?>
