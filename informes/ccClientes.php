<?php
	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');
	require(INC_PATH.'/fpdf/fpdf.php');

	/* POST */
	$fecha1 = $_POST['fecha_desde_ccCli'];
	$fecha2 = $_POST['fecha_hasta_ccCli'];

	/* CUENTA CORRIENTE */
	$do_cc = DB_DataObject::factory('cliente_cuenta_corriente');
	$cuenta_corriente = $do_cc -> getCC($fecha1,$fecha2);


	$pdf = new FPDF('P','mm','A4');
	
	foreach ($cuenta_corriente as $cc) {
    	$pdf->AddPage();
		// Titulo
	    $pdf->SetFont('Helvetica','',20);
	    $pdf->Cell(0, 10,'['.$cc['cliente_id'].'] '.$cc['cliente'],'B',1,'C');  
	    
	    // Subtitulo
	    $pdf->ln(2);
	    $pdf->SetFont('Helvetica','',12);
	    $pdf->Cell(0, 10,'Periodo '.date("d/m/Y",strtotime($fecha1)).' - '.date("d/m/Y",strtotime($fecha2)),'',1,'L');

	    // Cabecera de la tabla
	    $pdf->SetFillColor(209,212,226);
	    $pdf->SetTextColor(0,0,0);
	    $pdf->SetFont('Helvetica','B',10);
	    $pdf->Cell(30,7,'Fecha',0,0,'L',true);
		$pdf->Cell(30,7,'Concepto',0,0,'L',true);  
		$pdf->Cell(15,7,'1/2',0,0,'L',true);
		$pdf->Cell(18,7,'Total KG',0,0,'L',true);   
		$pdf->Cell(12,7,'Precio u. KG',0,0,'L',true);
		$pdf->Cell(25,7,'Importe',0,0,'R',true);  
		$pdf->Cell(25,7,'Pago',0,0,'R',true);
		$pdf->Cell(30,7,'Saldo',0,0,'R',true);   
	    $pdf->ln(6);

	    $pdf->SetTextColor(40,40,40);

	    $x = 10;

	    $color = false;
	    $saldo = 0;

	    $pdf->SetFont('Helvetica','',10);
	    foreach ($cc['cc'] as $asiento) {
	        if($color){
	            $pdf->SetFillColor(240,241,241);
	        } else {
	            $pdf->SetFillColor(250,251,251);
	        }

	        $pdf->Cell(30,5,date("d/m/y",strtotime($asiento['fecha'])),0,0,'L',true);
	        $pdf->Cell(30,5,$asiento['concepto'],0,0,'L',true); 
	        $pdf->Cell(15,5,$asiento['medias'],0,0,'L',true); 
	        $pdf->Cell(18,5,$asiento['tot_kg'],0,0,'L',true); 
	        $pdf->Cell(12,5,$asiento['p_unit_kg'],0,0,'L',true);
	        $pdf->Cell(25,5,$asiento['importe'],0,0,'R',true); 
	        $pdf->Cell(25,5,$asiento['pago'],0,0,'R',true); 
	        $pdf->Cell(30,5,$asiento['saldo'],0,1,'R',true); 
	        $pdf->ln(2);

	        if($color){ 
	            $color = false; 
	        } else {
	            $color = true;
	        }
	        $saldo = $asiento['saldo'];
    	}

    	$pdf->ln(2);
    	$pdf->SetFillColor(250,251,251);
    	$pdf->SetFont('Helvetica','B',12);
    	$pdf->Cell(30,7,"",0,0,'L',true);
        $pdf->Cell(30,7,"",0,0,'L',true); 
        $pdf->Cell(15,7,"",0,0,'L',true); 
        $pdf->Cell(15,7,"",0,0,'L',true); 
        $pdf->Cell(15,7,"",0,0,'L',true);
        $pdf->Cell(15,7,"",0,0,'R',true); 
        $pdf->SetFillColor(240,241,241);
        $pdf->Cell(35,7,"SALDO FINAL",0,0,'R',true); 
        $pdf->Cell(30,7,$asiento['saldo'],0,1,'R',true); 
        $pdf->ln(2);
		
	}

    $nombre_arch= 'resumen.pdf';

    $pdf -> setDisplayMode('fullpage'); 
    $pdf-> Output($nombre_arch,'I',true);

    exit;

?>
