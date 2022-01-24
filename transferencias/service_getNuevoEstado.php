<?php

    header('Content-Type: application/json');

    require_once('../config/web.config');
    require_once(CFG_PATH.'/data.config');
    require_once(INC_PATH.'/pear.inc');
    require_once(INC_PATH.'/comun.php');
    ob_clean();
   // Consulto si alguna transferencia pendiente cambio de estado en el sistema matriz   
    $ch = curl_init();

    $transferencias_pendientes = DB_DataObject::factory('transferencias');
    $transferencias_pendientes -> whereAdd('transf_tipo = 1 and transf_estado in(2,3)'); //Enviada y recibida por sistema matriz
    $transferencias_pendientes -> find();

    while ($transferencias_pendientes -> fetch()) {
        $pendientes[] = $transferencias_pendientes -> transf_matriz_id;
    }

    curl_setopt($ch, CURLOPT_URL,SRV_PATH."/services/getNuevoEstado.php");
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($pendientes)); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec ($ch);
    curl_close ($ch);
    $datos = json_decode($data,true);
    //print_r($datos);exit;
    $i = 0;
    foreach ($datos as $k => $v) {
        $transferencias_update = DB_DataObject::factory('transferencias');
        $transferencias_update -> transf_id = $v['transf_puesto_id'];
        $transferencias_update -> find(true);

        switch ($v['estado']) {
            case 2:
                $transferencias_update -> transf_estado = 4;        //Aceptada
                break;
            case 3:
                $transferencias_update -> transf_estado = 6;        //Rechazada
                break;
            case 4:
                $transferencias_update -> transf_estado = 5;        //Aceptada con diferencia de stock
                break;

             case 5:
                $transferencias_update -> transf_estado = 3;        //Recibida por puesto destino
                break;
        }

        $transferencias_update -> transf_costo_descarga = $v['transf_costo_total_descarga'];
        $transferencias_update -> update();
        $i++;
        foreach ($v['detalle'] as $key => $value) {
            $transferencias_detalle_update = DB_DataObject::factory('transferencias_detalle');
            $transferencias_detalle_update -> whereAdd('detalle_lote = '.$value['lote'].' AND detalle_transferencia_id = '.$v['transf_puesto_id'].' and detalle_producto_id = '.$value['producto_id']);
            $transferencias_detalle_update -> find(true);

            $transferencias_detalle_update -> detalle_costo_descarga = $value['costo_descarga'];
            $transferencias_detalle_update -> detalle_producto_cantidad_destino = $value['cant_destino'];
            $transferencias_detalle_update -> update();

            if($value['diferencia_stock']){
                //Perdida de mercaderia -> no hago nada... ahora tiene la opcion de elegir que hacer con la dif.. si es perdida o se repone el stock
                // $do_perdida_mercaderia = DB_DataObject::factory('perdida_mercaderia');
                // $do_perdida_mercaderia -> perdida_ps_id = $value['lote'];
                // $do_perdida_mercaderia -> perdida_obs = "Perdida de mercaderia en transferencia: ".$v['transf_puesto_id'];
                // $do_perdida_mercaderia -> perdida_cantidad = $value['diferencia_stock'];
                // $do_perdida_mercaderia -> insert();
            }else{
                //le llego mas mercaderia de lo que le mande, en algun lado hay que impactarlo

            }

        }        
    }

    echo $i;
    exit;
?>
