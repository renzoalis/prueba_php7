<?php
    function getTransferenciasAdmin(){
        // abrimos la sesión cURL
        $ch = curl_init();
         
        // definimos la URL a la que hacemos la petición
        //curl_setopt($ch, CURLOPT_URL,"http://demo.dev-gam.com.ar/mercado_admin/services/cargarTransferencia.php");
        curl_setopt($ch, CURLOPT_URL,SRV_PATH."/services/getTransferenciasPuesto.php");

        // indicamos el tipo de petición: POST
        curl_setopt($ch, CURLOPT_POST, TRUE);
        $array['puesto'] = PUESTO_ID;
        // definimos cada uno de los parámetros
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($array)); 
         
        // recibimos la respuesta y la guardamos en una variable
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $remote_server_output = curl_exec ($ch);
        //print_r($remote_server_output);exit;
         
        // cerramos la sesión cURL
        curl_close ($ch);
         
        // hacemos lo que queramos con los datos recibidos
        // por ejemplo, los mostramos
        //print_r($remote_server_output);exit;
        return $remote_server_output;   
    }

?>