<?php
	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');
	require('fpdf/fpdf.php');

	/* POST */
	$fecha_d = $_POST['fecha_ventas_d'];
    $fecha_h = $_POST['fecha_ventas_h'];

	/* CUENTA CORRIENTE */
	$do_cc = DB_DataObject::factory('venta');
	$cc_ventas = $do_cc -> getListadoVentasDiarias($fecha_d,$fecha_h);

	$dia = date('d/m/Y',strtotime($fecha_d)).' - '.date('d/m/Y',strtotime($fecha_h));

/* ARMO EXCEL */
    $workbook = new Spreadsheet_Excel_Writer();
    $workbook->setVersion(8);
    $workbook->send('listado_ventas_'.$fecha_h.'.xls');

    $worksheet = $workbook->addWorksheet('Ventas');
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

    $format_footer =& $workbook->addFormat();
    $format_footer->setAlign('right');
    $format_footer->setSize(13);
    $format_footer->setColor('black');
    $format_footer->setBold();

    /* TITULOS */
    $worksheet->write(0, 0, 'Ventas diarias '.$dia,$format_title);
    $worksheet->write(0, 1, '',$format_title);
    $worksheet->write(0, 2, '',$format_title);
    $worksheet->write(0, 3, '',$format_title);
    $worksheet->write(0, 4, '',$format_title);
    $worksheet->write(0, 5, '',$format_title);
    $worksheet->write(0, 6, '',$format_title);
    $worksheet->write(0, 7, '',$format_title);

    /* COLUMNAS */
    $worksheet->write(2, 0, 'ID',$format_column);
    $worksheet->write(2, 1, 'Cliente',$format_column);
    $worksheet->write(2, 2, 'Fecha',$format_column);
    $worksheet->write(2, 3, 'Medias',$format_column);
    $worksheet->write(2, 4, 'K prom',$format_column);
    $worksheet->write(2, 5, 'Kilos',$format_column);
    $worksheet->write(2, 6, 'Precio u.',$format_column);
    $worksheet->write(2, 7, 'Total',$format_column);

    $row = 2;
    $cant_total = 0;
    $tot_medias = 0;
    $tot_proms = 0;
    $tot_kgs = 0;
    $tot_pun = 0;
    $tot_pago = 0;

    /* FILAS */
    foreach ($cc_ventas as $cc) {
        $row++;
        $worksheet->write($row, 0, $cc['nro']);
        $worksheet->write($row, 1, $cc['cli']);
        $worksheet->write($row, 2, $cc['fecha']);
        $worksheet->write($row, 3, $cc['medias'],$format_derecha);
        $worksheet->write($row, 4, number_format($cc['k_prom'],2,',','.').' Kg.',$format_derecha);
        $worksheet->write($row, 5, $cc['k_tot'],$format_derecha);
        $worksheet->write($row, 6, '$ '.number_format($cc['p_unit'],2,',','.'),$format_derecha);
        $worksheet->write($row, 7, '$ '.number_format($cc['pago'],2,',','.'),$format_derecha);

        $cant_total += 1;
        $tot_medias += $cc['medias'];
        $tot_proms += $cc['k_prom'];
        $tot_kgs += $cc['k_tot'];
        $tot_pun += $cc['p_unit'];
        $tot_pago += $cc['pago'];
    }

    $row = $row + 2;
    $worksheet->write($row, 0, '');
    $worksheet->write($row, 1, '');
    $worksheet->write($row, 2, '');
    $worksheet->write($row, 3, $tot_medias,$format_footer);
    $worksheet->write($row, 4, number_format($tot_proms/$cant_total,2,',','.').' Kg.',$format_footer);
    $worksheet->write($row, 5, $tot_kgs.' Kg.',$format_footer);
    $worksheet->write($row, 6, '$ '.number_format($tot_pun/$cant_total,2,',','.'),$format_footer);
    $worksheet->write($row, 7, '$ '.number_format($tot_pago,2,',','.'),$format_footer);

    ob_clean(); 
    $workbook->close();

    exit;
?>
