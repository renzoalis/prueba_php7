<?php
/**
 * Table Definition for transportista
 */
require_once 'DB/DataObject.php';

class DataObjects_Transportista extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'transportista';       // table name
    public $transportista_id;                // int(11)  not_null primary_key auto_increment group_by
    public $transportista_nombre;            // varchar(256)  not_null
    public $transportista_email;             // varchar(256)  
    public $transportista_tel1;              // varchar(128)  
    public $transportista_emp_nombre;        // varchar(128)  
    public $transportista_dni;               // varchar(128)  
    public $transportista_observacion;       // blob(65535)  blob
    public $transportista_direccion;         // varchar(256)  
    public $transportista_fh_alta;           // datetime(19)  not_null
    public $transportista_baja;              // tinyint(1)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Transportista',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    function nuevoTransportista($objeto) {
        $do_transportista = DB_DataObject::factory('transportista');
        $do_transportista -> transportista_nombre = $objeto['input_nombre'];
        $do_transportista -> transportista_tel1 = $objeto['input_telefono_1'];
        $do_transportista -> transportista_observacion = $objeto['input_observacion'];
        $do_transportista -> transportista_dni = $objeto['input_dni'];
        $do_transportista -> transportista_emp_nombre = $objeto['input_empresa'];
        $do_transportista -> transportista_direccion = $objeto['input_direccion'];
        $do_transportista -> transportista_email = $objeto['input_email'];
        $do_transportista -> transportista_fh_alta = date('Y-m-d H:i:s');
        $do_transportista -> transportista_baja = 0;

        $id_insert = $do_transportista -> insert();
        return $id_insert;
    }


    function editTransportista($objeto) {
        $this -> transportista_id = $objeto['edit_transportista_id'];
        $this -> find(true);

        $this -> transportista_nombre = $objeto['input_nombre'];
        $this -> transportista_tel1 = $objeto['input_telefono_1'];
        $this -> transportista_observacion = $objeto['input_observacion'];
        $this -> transportista_dni = $objeto['input_dni'];
        $this -> transportista_emp_nombre = $objeto['input_empresa'];
        $this -> transportista_direccion = $objeto['input_direccion'];
        $this -> transportista_email = $objeto['input_email'];
        $this -> transportista_fh_alta = date('Y-m-d H:i:s');
        $this -> transportista_baja = 0;

        $this -> update();
        return $this -> transportista_id;
    }

    function eliminarTransportista($objeto) {
        $this -> transportista_id = $objeto['id_eliminar'];
        $this -> find(true);
    
        $this -> transportista_baja = 1;

        $this -> update();
        return $this -> transportista_id;
    }

    function getTransportistas($id=false){
        if($id){
            $this -> transportista_id = $id;
            $this -> find(true);
        } else {
            $this -> transportista_baja = 0;
            $this -> orderBy('transportista_id ASC');
            $this -> find();
        }

        return $this;
    }

    function getSaldo(){
        $do_cc = DB_DataObject::factory('transportista_cuenta_corriente');
        $do_cc -> ccte_transportista_id = $this -> transportista_id;
        $do_cc -> orderBy('ccte_id DESC');
        $do_cc -> find(true);

        if($do_cc -> ccte_saldo_actual >= 0){
            return '<span class="cc_verde" >'.$do_cc -> ccte_saldo_actual.'</span>';
        }else{
              return '<span class="cc_rojo" >'.$do_cc -> ccte_saldo_actual.'</span>';
        }
    }   

}
