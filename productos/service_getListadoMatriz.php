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

    $respuesta = json_decode($data,true);

    // print_r($data);exit;
    if($respuesta['respuesta']){

        foreach ($respuesta['prod'] as $key => $value) {
            $do_productos_update = DB_DataObject::factory('producto');
            
            $do_productos_update -> prod_nombre = $value['prod_nombre'];
            $do_productos_update -> prod_alias = $value['prod_alias'];
            $do_productos_update -> prod_codigo = $value['prod_codigo'];
            $do_productos_update -> prod_origen = $value['prod_origen'];
            $do_productos_update -> prod_presentacion = $value['prod_presentacion'];
            $do_productos_update -> prod_baja = $value['prod_baja'];
            $do_productos_update -> prod_cat_id = $value['prod_cat_id'];

            $do_productos_update -> insert();

        }


        foreach ($respuesta['cat'] as $key => $value) {

            $do_categoria_update = DB_DataObject::factory('categoria');
            
            $do_categoria_update -> cat_nombre = $value['cat_nombre'];
            $do_categoria_update -> cat_baja = $value['cat_baja'];
            $do_categoria_update -> cat_tipo_id = $value['cat_tipo_id'];

            $do_categoria_update -> insert();

        }

        foreach ($respuesta['tipo'] as $key => $value) {

            $do_tipo_update = DB_DataObject::factory('tipo');
            
            $do_tipo_update -> tipo_nombre = $value['tipo_nombre'];
            $do_tipo_update -> tipo_baja = $value['tipo_baja'];
            $do_tipo_update -> tipo_desc = $value['tipo_desc'];
            
            $do_tipo_update -> insert();

        }


        echo json_encode(1);

    }else{
        echo json_encode(0);
    }
?>
