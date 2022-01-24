<?php

    header('Content-Type: application/json');

    require_once('../config/web.config');
    require_once(CFG_PATH.'/data.config');
    require_once(INC_PATH.'/pear.inc');
    require_once(INC_PATH.'/comun.php');

    // Consulto si hay trasnferencias nuevas   
    $ch = curl_init();
    $array['puesto'] = PUESTO_ID;
    
    // echo SRV_PATH."/services/getTransferenciasPuesto.php";exit;

    curl_setopt($ch, CURLOPT_URL,SRV_PATH."/services/getTransferenciasPuesto.php");

    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($array)); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec ($ch);
    curl_close ($ch);

    $respuesta = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data), true );
    // var_dump($respuesta);exit;
    if($respuesta['respuesta']){
        $servicio_transferencia = DB_DataObject::factory('transferencias');
        $actualizar = $servicio_transferencia -> cargarTransferenciasService($respuesta);
        $total_transferencias = $actualizar['cantidad'];
    }

    // Actualizo las trasnferencias insertadas en el sistema matriz
    $ch_actualizar = curl_init();
    if($respuesta['respuesta']){

        curl_setopt($ch_actualizar, CURLOPT_URL,SRV_PATH."/services/updateTransferenciasPuesto.php");
        curl_setopt($ch_actualizar, CURLOPT_POST, TRUE);
        curl_setopt($ch_actualizar, CURLOPT_POSTFIELDS, http_build_query($actualizar)); 
        curl_setopt($ch_actualizar, CURLOPT_RETURNTRANSFER, true);
        $data_actualizar = curl_exec ($ch_actualizar);
        curl_close ($ch_actualizar);

        if($data_actualizar == "OK"){
          $notificacion_exito = "Todas las transferencias fueron sincronizadas correctamente";
        }
    }     

    if($respuesta['respuesta']){
        echo $total_transferencias;
    } else {
        echo 0;
    }
    exit;
?>
