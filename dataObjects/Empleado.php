<?php
/**
 * Table Definition for empleado
 */
require_once 'DB/DataObject.php';

class DataObjects_Empleado extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'empleado';            // table name
    public $empleado_id;                     // int(11)  not_null primary_key auto_increment group_by
    public $empleado_nombre;                 // varchar(256)  not_null
    public $empleado_email;                  // varchar(256)  
    public $empleado_tel1;                   // varchar(128)  
    public $empleado_emp_nombre;             // varchar(128)  
    public $empleado_dni;                    // varchar(128)  
    public $empleado_observacion;            // blob(65535)  blob
    public $empleado_direccion;              // varchar(256)  
    public $empleado_fh_alta;                // datetime(19)  not_null
    public $empleado_baja;                   // tinyint(1)  not_null group_by
    public $empleado_matriz_id;              // int(11)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Empleado',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    function nuevoEmpleado($objeto) {
        $this -> empleado_nombre = $objeto['input_nombre'];
        $this -> empleado_tel1 = $objeto['input_telefono_1'];
        $this -> empleado_observacion = $objeto['input_observacion'];
        $this -> empleado_dni = $objeto['input_dni'];
        $this -> empleado_emp_nombre = $objeto['input_empresa'];
        $this -> empleado_direccion = $objeto['input_direccion'];
        $this -> empleado_email = $objeto['input_email'];
        $this -> empleado_fh_alta = date('Y-m-d H:i:s');
        $this -> empleado_baja = 0;

        $id_insert = $this -> insert();

        $objeto['empleado_id'] = $id_insert;
        $objeto['puesto_id'] = PUESTO_ID;

        $id_matriz = $this -> cargarEmpleado($objeto);
        $this -> empleado_matriz_id = (int) $id_matriz;
        $this -> update();


        return $id_insert;
    }

    function cargarEmpleado($objeto){
        // abrimos la sesión cURL
        $ch = curl_init();
         
        // definimos la URL a la que hacemos la petición
        //curl_setopt($ch, CURLOPT_URL,"http://demo.dev-gam.com.ar/mercado_admin/services/cargarTransferencia.php");
        curl_setopt($ch, CURLOPT_URL,SRV_PATH."/services/cargarEmpleado.php");

        // indicamos el tipo de petición: POST
        curl_setopt($ch, CURLOPT_POST, TRUE);

        // definimos cada uno de los parámetros
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($objeto));
         
        // recibimos la respuesta y la guardamos en una variable
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $remote_server_output = curl_exec ($ch);
         
        // cerramos la sesión cURL
        curl_close ($ch);
         
        // hacemos lo que queramos con los datos recibidos
        // por ejemplo, los mostramos
        //print_r($remote_server_output);exit;
        return $remote_server_output;   
    }

    function editEmpleado($objeto) {
        $this -> empleado_id = $objeto['edit_empleado_id'];
        $this -> find(true);

        $this -> empleado_nombre = $objeto['input_nombre'];
        $this -> empleado_tel1 = $objeto['input_telefono_1'];
        $this -> empleado_observacion = $objeto['input_observacion'];
        $this -> empleado_dni = $objeto['input_dni'];
        $this -> empleado_emp_nombre = $objeto['input_empresa'];
        $this -> empleado_direccion = $objeto['input_direccion'];
        $this -> empleado_email = $objeto['input_email'];
        $this -> empleado_fh_alta = date('Y-m-d H:i:s');
        $this -> empleado_baja = 0;

        $this -> update();

        $id_matriz = $this -> editEmpleadoservice($objeto);
        return $id_matriz;
    }

       function editEmpleadoservice($objeto){
        // abrimos la sesión cURL
        $ch = curl_init();
         
        // definimos la URL a la que hacemos la petición
        //curl_setopt($ch, CURLOPT_URL,"http://demo.dev-gam.com.ar/mercado_admin/services/cargarTransferencia.php");
        curl_setopt($ch, CURLOPT_URL,SRV_PATH."/services/editEmpleado.php");

        // indicamos el tipo de petición: POST
        curl_setopt($ch, CURLOPT_POST, TRUE);

        // definimos cada uno de los parámetros
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($objeto));
         
        // recibimos la respuesta y la guardamos en una variable
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $remote_server_output = curl_exec ($ch);
         
        // cerramos la sesión cURL
        curl_close ($ch);
         
        // hacemos lo que queramos con los datos recibidos
        // por ejemplo, los mostramos
        //print_r($remote_server_output);exit;
        return $remote_server_output;   
    }

    function eliminarEmpleado($objeto) {
        $this -> empleado_id = $objeto['id_eliminar'];
        $this -> find(true);
    
        $this -> empleado_baja = 1;

        $this -> update();
        return $this -> empleado_id;
    }

    function getEmpleados($id=false){
        if($id){
            $this -> empleado_id = $id;
            $this -> find(true);
        } else {
            $this -> empleado_baja = 0;
            $this -> find();
        }

        return $this;
    }

    function getSaldo(){
        $do_cc = DB_DataObject::factory('empleado_cuenta_corriente');
        $do_cc -> ccte_empleado_id = $this -> empleado_id;
        $do_cc -> orderBy('ccte_id DESC');
        $do_cc -> find(true);

        if($do_cc -> ccte_saldo_actual >= 0){
            return '<span class="cc_verde" >'.$do_cc -> ccte_saldo_actual.'</span>';
        }else{
              return '<span class="cc_rojo" >'.$do_cc -> ccte_saldo_actual.'</span>';
        }
    }   
}
