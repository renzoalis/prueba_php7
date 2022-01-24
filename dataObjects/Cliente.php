<?php
/**
 * Table Definition for cliente
 */
require_once 'DB/DataObject.php';

class DataObjects_Cliente extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'cliente';             // table name
    public $cliente_id;                      // int(11)  not_null primary_key auto_increment group_by
    public $cliente_nombre;                  // varchar(256)  not_null
    public $cliente_email;                   // varchar(128)  
    public $cliente_tel1;                    // varchar(64)  
    public $cliente_trabajo;                 // varchar(64)  
    public $cliente_dni;                     // varchar(64)  
    public $cliente_observacion;             // blob(65535)  blob
    public $cliente_direccion;               // varchar(256)  
    public $cliente_fh_alta;                 // datetime(19)  not_null
    public $cliente_baja;                    // tinyint(1)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Cliente',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function nuevoCliente($objeto) {

        $do_cliente = DB_DataObject::factory('cliente');
        $do_cliente -> cliente_nombre = $objeto['input_nombre'];
        $do_cliente -> cliente_tel1 = $objeto['input_telefono_1'];
        $do_cliente -> cliente_observacion = $objeto['input_observacion'];
        $do_cliente -> cliente_dni = $objeto['input_dni'];
        $do_cliente -> cliente_trabajo = $objeto['input_trabajo'];
        $do_cliente -> cliente_direccion = $objeto['input_direccion'];
        $do_cliente -> cliente_email = $objeto['input_email'];
        $do_cliente -> cliente_fh_alta = date('Y-m-d H:i:s');
        $do_cliente -> cliente_baja = 0;

        $id_insert = $do_cliente -> insert();
        return ($id_insert);
    }

    function editCliente($objeto) {
        $this -> cliente_id = $objeto['edit_cliente_id'];
        $this -> find(true);

        $this -> cliente_nombre = $objeto['input_nombre'];
        $this -> cliente_tel1 = $objeto['input_telefono_1'];
        $this -> cliente_observacion = $objeto['input_observacion'];
        $this -> cliente_dni = $objeto['input_dni'];
        $this -> cliente_trabajo = $objeto['input_trabajo'];
        $this -> cliente_direccion = $objeto['input_direccion'];
        $this -> cliente_email = $objeto['input_email'];
        $this -> cliente_fh_alta = date('Y-m-d H:i:s');
        $this -> cliente_baja = 0;

        $this -> update();
        return $this -> cliente_id;
    }

    function eliminarCliente($objeto) {
        $this -> cliente_id = $objeto['id_eliminar'];
        $this -> find(true);
    
        $this -> cliente_baja = 1;

        $this -> update();
        return $this -> cliente_id;
    }

    function getClientes($id=false){
        if($id){
            $this -> cliente_id = $id;
            $this -> find(true);
        } else {
            $this -> cliente_baja = 0;
            $this -> find();
        }

        return $this;
    }

    function getSaldo(){
        $do_cc = DB_DataObject::factory('cliente_cuenta_corriente');
        $do_cc -> ccte_cliente_id = $this -> cliente_id;
        $do_cc -> orderBy('ccte_id DESC');
        $do_cc -> find(true);


        if($do_cc -> ccte_saldo_actual >= 0){
            return '<span class="cc_verde" >'.$do_cc -> ccte_saldo_actual.'</span>';
        }else{
              return '<span class="cc_rojo" >'.$do_cc -> ccte_saldo_actual.'</span>';
        }
    }

    function getSaldoValor(){
        $do_cc = DB_DataObject::factory('cliente_cuenta_corriente');
        $do_cc -> ccte_cliente_id = $this -> cliente_id;
        $do_cc -> orderBy('ccte_id DESC');

        if($do_cc -> find(true)){
            return $do_cc -> ccte_saldo_actual;
        }else{
            return 0;
        }
    }
}
