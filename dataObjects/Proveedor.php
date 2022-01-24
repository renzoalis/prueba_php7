<?php
/**
 * Table Definition for proveedor
 */
require_once 'DB/DataObject.php';

class DataObjects_Proveedor extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'proveedor';           // table name
    public $prov_id;                         // int(11)  not_null primary_key auto_increment group_by
    public $prov_nombre;                     // varchar(256)  not_null
    public $prov_email;                      // varchar(256)  
    public $prov_tel1;                       // varchar(128)  
    public $prov_dni;                        // varchar(128)  
    public $prov_observacion;                // blob(65535)  blob
    public $prov_direccion;                  // varchar(256)  
    public $prov_fh_alta;                    // datetime(19)  not_null
    public $prov_baja;                       // tinyint(1)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Proveedor',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function nuevoProveedor($objeto) {

        $this -> prov_nombre = $objeto['input_nombre'];
        $this -> prov_tel1 = $objeto['input_telefono_1'];
        $this -> prov_observacion = $objeto['input_observacion'];
        $this -> prov_dni = $objeto['input_dni'];
        $this -> prov_trabajo = $objeto['input_trabajo'];
        $this -> prov_direccion = $objeto['input_direccion'];
        $this -> prov_email = $objeto['input_email'];
        $this -> prov_fh_alta = date('Y-m-d H:i:s');
        $this -> prov_baja = 0;

        $id_insert = $this -> insert();
        return ($id_insert);
    }

    function editProveedor($objeto) {
        $this -> prov_id = $objeto['edit_prov_id'];
        $this -> find(true);

        $this -> prov_nombre = $objeto['input_nombre'];
        $this -> prov_tel1 = $objeto['input_telefono_1'];
        $this -> prov_observacion = $objeto['input_observacion'];
        $this -> prov_dni = $objeto['input_dni'];
        $this -> prov_trabajo = $objeto['input_trabajo'];
        $this -> prov_direccion = $objeto['input_direccion'];
        $this -> prov_email = $objeto['input_email'];
        $this -> prov_fh_alta = date('Y-m-d H:i:s');
        $this -> prov_baja = 0;

        $this -> update();
        return $this -> prov_id;
    }

    function eliminarProveedor($objeto) {
        $this -> prov_id = $objeto['id_eliminar'];
        $this -> find(true);
    
        $this -> prov_baja = 1;

        $this -> update();
        return $this -> prov_id;
    }

    function getProveedores($id=false){
        if($id){
            $this -> prov_id = $id;
            $this -> find(true);
        } else {
            $this -> prov_baja = 0;
            $this -> find();
        }

        return $this;
    }

    function getSaldo(){
        $do_cc = DB_DataObject::factory('proveedor_cuenta_corriente');
        $do_cc -> ccte_proveedor_id = $this -> prov_id;
        $do_cc -> orderBy('ccte_id DESC');
        $do_cc -> find(true);


        if($do_cc -> ccte_saldo_actual >= 0){
            return '<span class="cc_verde" >'.$do_cc -> ccte_saldo_actual.'</span>';
        }else{
              return '<span class="cc_rojo" >'.$do_cc -> ccte_saldo_actual.'</span>';
        }
    }


    function getSaldoValor(){
        $do_cc = DB_DataObject::factory('proveedor_cuenta_corriente');
        $do_cc -> ccte_proveedor_id = $this -> proveedor_id;
        $do_cc -> orderBy('ccte_id DESC');

        if($do_cc -> find(true)){
            return $do_cc -> ccte_saldo_actual;
        }else{
            return 0;
        }
    }
}
