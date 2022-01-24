<?php

    header('Content-Type: application/json');

    require_once('../config/web.config');
    require_once(CFG_PATH.'/data.config');
    require_once(INC_PATH.'/pear.inc');
    require_once(INC_PATH.'/comun.php');
    ob_clean();
    //DB_DataObject::debugLevel(1);
    $transferencias = DB_DataObject::factory('transferencias');
    $transferencias_detalle = DB_DataObject::factory('transferencias_detalle');
    $transferencias_detalle -> joinAdd($transferencias);

    $transferencias_detalle -> whereAdd('transf_tipo = 2 AND detalle_ppv != 0 AND detalle_ppv_fh_notif IS NULL'); //Tipo = recibida, pppv distinto de 0 y todavia no notificada a ppv
    $transferencias_detalle -> find();
    $objeto = array();
    $i = 0;
    while($transferencias_detalle -> fetch()){
        $objeto[$i]['producto_id'] = $transferencias_detalle -> detalle_producto_id;
        $objeto[$i]['calibre'] = $transferencias_detalle -> detalle_calibre;
        $objeto[$i]['transf_matriz_id'] = $transferencias_detalle -> transf_matriz_id;
        $objeto[$i]['ppv'] = $transferencias_detalle -> detalle_ppv;
        $i++;
    }      

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,SRV_PATH."/services/cargarPPVTransferencia.php");
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($objeto));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $datos = curl_exec ($ch);
    curl_close ($ch);

    $respuesta = json_decode($datos,true);

    //ACTUALIZO LA FECHA DE INFORME AL SISTEMA MATRIZ DEL PPV DEL PRODUCTO DE LA TRANSFERENCIA
    foreach ($respuesta as $key => $value) {
        $transferencia_detalle = DB_DataObject::factory('transferencias_detalle');
        $transferencia_detalle -> whereAdd('detalle_transferencia_id ='.$value['transf_puesto_id'].' AND detalle_calibre = "'.$value['calibre'].'" AND detalle_producto_id = '.$value['producto_id']);
        $transferencia_detalle -> find(true);

        $transferencia_detalle -> detalle_ppv_fh_notif = date('Y-m-d h:i:s');
        $transferencia_detalle -> update();
    }


    //CONSULTO SI HAY ALGUN PPV CARGADO DE LAS TRANSFERENCIAS QUE YO HICE
    $transferencias = DB_DataObject::factory('transferencias');
    $transferencia_detalle = DB_DataObject::factory('transferencias_detalle');

    $transferencias -> joinAdd($transferencia_detalle);
    $transferencias -> whereAdd('transf_tipo = 1 and transf_estado IN (4,5) AND detalle_ppv = 0');
    $transferencias -> find();

    $i = 0;
    while ($transferencias -> fetch()) {
        $objeto[$i]['producto_id'] = $transferencias -> detalle_producto_id;
        $objeto[$i]['calibre'] = $transferencias -> detalle_calibre;
        $objeto[$i]['matriz_id'] = $transferencias -> transf_matriz_id;
        $i ++;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,SRV_PATH."/services/getPPVTransferencia.php");
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($objeto));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $datos = curl_exec ($ch);
    curl_close ($ch);

    $respuesta = json_decode($datos,true);
    $i = 0;
    foreach ($respuesta as $key => $value) {
       $transferencia_detalle = DB_DataObject::factory('transferencias_detalle');
       $transferencia_detalle -> whereAdd('detalle_lote = '.$value["lote"].' AND detalle_transferencia_id = '.$value["puesto_id"].' AND detalle_producto_id = '.$value['producto_id'].' AND detalle_calibre = "'.$value['calibre'].'"');
       $transferencia_detalle -> find(true);

       $transferencia_detalle -> detalle_ppv = $value['ppv'];
       $i += $transferencia_detalle -> update();
    }
    echo $i;
    exit;
?>
