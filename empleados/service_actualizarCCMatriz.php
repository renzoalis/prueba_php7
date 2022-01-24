<?php

    header('Content-Type: application/json');

    require_once('../config/web.config');
    require_once(CFG_PATH.'/data.config');
    require_once(INC_PATH.'/pear.inc');
    require_once(INC_PATH.'/comun.php');
    
    $do_empleados = DB_DataObject::factory('empleado');
    $do_empleados -> whereAdd('empleado_matriz_id = 0');
    $do_empleados -> find();

    while($do_empleados -> fetch()){
        $objeto['input_nombre'] = $do_empleados -> empleado_nombre;
        $objeto['input_telefono_1'] = $do_empleados -> empleado_tel_1;
        $objeto['input_observacion'] = $do_empleados -> input_observacion;
        $objeto['input_dni'] = $do_empleados -> empleado_dni;
        $objeto['input_empresa'] = $do_empleados -> empleado_emp_nombre;
        $objeto['input_direccion'] = $do_empleados -> empleado_direccion;
        $objeto['input_email'] = $do_empleados -> empleado_email;
        $objeto['puesto_id'] = PUESTO_ID;
        $objeto['empleado_id'] = $do_empleados -> empleado_id;

        //CARGO LOS EMPLEADOS QUE TODAVIA NO SE ENVIARON A MATRIZ
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,SRV_PATH."/services/cargarEmpleado.php");
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($objeto));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $respuesta = curl_exec ($ch);
         
        curl_close ($ch);


        $do_empleados -> empleado_matriz_id = $respuesta;
        $do_empleados -> update();

    }

    // abrimos la sesión cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,SRV_PATH."/services/getUltimaCCEmp.php");
    curl_setopt($ch, CURLOPT_POST, TRUE);
    $objeto['id_puesto'] = $_POST['id'];

    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($objeto));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $ultimasCCEmp = curl_exec ($ch);
    curl_close ($ch);


    $ultimasCCEmp = json_decode($ultimasCCEmp,true);
    $listado_empleados_cc = array();
    foreach ($ultimasCCEmp as $key => $value) {
       $do_cc_empleados = DB_DataObject::factory('empleado_cuenta_corriente');
       $do_cc_empleados -> whereAdd('ccte_empleado_id ='.$key.' and ccte_id > '.$value['ultima_cc']);
       $do_cc_empleados -> find();

       $i=0;

       while($do_cc_empleados -> fetch()){
        $listado_empleados_cc[$value['empleado_matriz_id']][$i]['ccte_puesto_cc_id'] = $do_cc_empleados -> ccte_id;
        $listado_empleados_cc[$value['empleado_matriz_id']][$i]['ccte_fh'] = $do_cc_empleados -> ccte_fh;
        $listado_empleados_cc[$value['empleado_matriz_id']][$i]['ccte_operacion_tipo'] = $do_cc_empleados -> ccte_operacion_tipo;
        $listado_empleados_cc[$value['empleado_matriz_id']][$i]['ccte_operacion_id'] = $do_cc_empleados -> ccte_operacion_id;
        $listado_empleados_cc[$value['empleado_matriz_id']][$i]['ccte_importe'] = $do_cc_empleados -> ccte_importe;
        $listado_empleados_cc[$value['empleado_matriz_id']][$i]['ccte_saldo_actual'] = $do_cc_empleados -> ccte_saldo_actual;
        $i++;
       }
    }
        // abrimos la sesión cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,SRV_PATH."/services/actualizarCCEmp.php");
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($listado_empleados_cc));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $respuesta = curl_exec ($ch);
    curl_close ($ch);



    require_once('public/tablas.html');
    exit;
?>