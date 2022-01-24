<?php

    header('Content-Type: application/json');

    require_once('../config/web.config');
    require_once(CFG_PATH.'/data.config');
    require_once(INC_PATH.'/pear.inc');
    require_once(INC_PATH.'/comun.php');

    // Consulto si hay trasnferencias nuevas   
    $ch = curl_init();
    
    $do_productos = DB_DataObject::factory('producto');
    $array = $do_productos -> getUltimosIds();
    
    //$array['puesto'] = PUESTO_ID;



    curl_setopt($ch, CURLOPT_URL,SRV_PATH."/services/getListadoProductos.php");

    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($array)); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec ($ch);
    curl_close ($ch);

    $respuesta = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data), true );

    if($respuesta['respuesta']){
        $servicio_transferencia = DB_DataObject::factory('transferencias');
        $actualizar = $servicio_transferencia -> cargarTransferenciasService($respuesta);
        $total_transferencias = $actualizar['cantidad'];
    }
    //var_dump($respuesta);exit;
?>
