<?php

    header('Content-Type: application/json');

    require_once('../config/web.config');
    require_once(CFG_PATH.'/data.config');
    require_once(INC_PATH.'/pear.inc');
    require_once(INC_PATH.'/comun.php');

    // Consulto si alguna transferencia pendiente de envio
    $ch = curl_init();

    $transferencias_pendientes = DB_DataObject::factory('transferencias');
    $transferencias_pendientes -> whereAdd('transf_tipo = 1 and transf_estado = 1'); //Enviada y pendiente de envio
    $transferencias_pendientes -> find();

    // print_r($transferencias_pendientes);exit;
    $i=0;
    while ($transferencias_pendientes -> fetch()) {

        $transferencias_detalle = DB_DataObject::factory('transferencias_detalle');
        $transferencias_detalle -> whereAdd('detalle_transferencia_id = '.$transferencias_pendientes -> transf_id);
        $transferencias_detalle -> find();
    
        $objeto['transf_puesto_id'] = $transferencias_pendientes -> transf_id;
        $objeto['puesto_id'] = PUESTO_ID;
        $objeto['input_tipo'] = $transferencias_pendientes -> transf_tipo; 
        $objeto['transf_origen'] = PUESTO_ID;
        $objeto['transf_destino'] = $transferencias_pendientes -> transf_destino;
        $objeto['cant_prod'] = $transferencias_pendientes -> transf_cant;

        $objeto['input_observacion_transferencia'] = $transferencias_pendientes -> transf_obs;
        $objeto['costo_final_total'] = $transferencias_pendientes -> transf_costo_carga;

        while ($transferencias_detalle -> fetch()) {
            $productos = DB_DataObject::factory('producto');
            $productos -> prod_id = $transferencias_detalle -> detalle_producto_id;
            $productos -> find(true); 

            $objeto['prod'][$productos -> prod_id]['id'] = $transferencias_detalle -> detalle_producto_id;
            $objeto['prod'][$productos -> prod_id]['calibre'] = $transferencias_detalle -> detalle_calibre;
            $objeto['prod'][$productos -> prod_id]['cantidad'] = $transferencias_detalle -> detalle_producto_cantidad_origen;
            $objeto['prod'][$productos -> prod_id]['precio_carga_unitaria'] = $transferencias_detalle -> detalle_costo_carga;
            $objeto['prod'][$productos -> prod_id]['precio_flete_unitario'] = $transferencias_detalle -> detalle_costo_flete;
            $objeto['prod'][$productos -> prod_id]['costou'] = $transferencias_detalle -> detalle_costo_unitario;
         
        }

        $id_transf_matriz = $transferencias_pendientes -> enviarTransferencia($objeto);

        if($id_transf_matriz){ // El sistema matriz recibio la transferencia
            $transferencias_pendientes -> transf_estado = 2;
            $transferencias_pendientes -> transf_matriz_id = (int) $id_transf_matriz;
            $update = $transferencias_pendientes -> update();
            $i++;
        }
    }
    echo $i;
    exit;
?>
