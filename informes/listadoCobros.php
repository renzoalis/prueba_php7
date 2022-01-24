<?php
	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');
	require('fpdf/fpdf.php');

	/* POST */
	$fecha = $_POST['fecha_cobros'];

	/* CUENTA CORRIENTE */
	$do_cc = DB_DataObject::factory('cobro_cliente');
	$cc = $do_cc -> getListadoCobrosDiarios($fecha);

	$dia = date('d/m/Y',strtotime($fecha));


	$pdf = new FPDF('P','mm','A4');
	
	$pdf->AddPage();
	// Titulo
    $pdf->SetFont('Helvetica','',20);
    $pdf->Cell(0, 10,'PAGOS CARNICERIA','B',1,'C');  
    
    // Subtitulo
    $pdf->ln(2);
    $pdf->SetFont('Helvetica','',12);
    $pdf->Cell(0, 10,'Dia '.$dia,'',1,'C');

    // Cabecera de la tabla
    $pdf->SetFillColor(209,212,226);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('Helvetica','B',10);
    $pdf->Cell(30,7,'Nro.',0,0,'L',true);
	$pdf->Cell(70,7,'Cliente',0,0,'L',true);  
	$pdf->Cell(45,7,'Item',0,0,'L',true);
	$pdf->Cell(45,7,'Pago',0,0,'R',true);   
    $pdf->ln(6);

    $pdf->SetTextColor(40,40,40);

    $x = 10;

    $color = false;
    $saldo = 0;

    $pdf->SetFont('Helvetica','',10);
	
    foreach ($cc as $asiento) {
        if($color){
            $pdf->SetFillColor(240,241,241);
        } else {
            $pdf->SetFillColor(250,251,251);
        }

        $pdf->Cell(30,5,$asiento['nro'],0,0,'L',true); 
        $pdf->Cell(70,5,$asiento['cli'],0,0,'L',true);
        $pdf->Cell(45,5,$asiento['item'],0,0,'L',true); 
        $pdf->Cell(45,5,'$ '.$asiento['pago'],0,1,'R',true); 
        $pdf->ln(2);

        if($color){ 
            $color = false; 
        } else {
            $color = true;
        }
        $saldo += $asiento['pago'];
	}

	$pdf->ln(2);
	$pdf->SetFillColor(250,251,251);
	$pdf->SetFont('Helvetica','B',12);
	$pdf->Cell(30,7,"",0,0,'L',true);
    $pdf->Cell(70,7,"",0,0,'L',true); 
    $pdf->SetFillColor(240,241,241);
    $pdf->Cell(45,7,"TOTAL PAGOS",0,0,'R',true); 
    $pdf->Cell(45,7,'$ '.$saldo,0,1,'R',true); 
    $pdf->ln(2);
		
    $nombre_arch= 'listadoCobros.pdf';

    $pdf -> setDisplayMode('fullpage'); 
    $pdf-> Output($nombre_arch,'I',true);

	exit;
?>
