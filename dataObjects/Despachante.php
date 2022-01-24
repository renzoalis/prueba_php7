<?php
/**
 * Table Definition for despachante
 */
require_once 'DB/DataObject.php';

class DataObjects_Despachante extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'despachante';         // table name
    public $despachante_id;                  // int(11)  not_null primary_key auto_increment group_by
    public $despachante_nombre;              // varchar(256)  not_null
    public $despachante_email;               // varchar(256)  
    public $despachante_tel1;                // varchar(128)  
    public $despachante_dni;                 // varchar(128)  
    public $despachante_observacion;         // blob(65535)  blob
    public $despachante_direccion;           // varchar(256)  
    public $despachante_fh_alta;             // datetime(19)  not_null
    public $despachante_baja;                // tinyint(1)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Despachante',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

     function nuevoDespachante($objeto) {

        $this -> despachante_nombre = $objeto['input_nombre'];
        $this -> despachante_tel1 = $objeto['input_telefono_1'];
        $this -> despachante_observacion = $objeto['input_observacion'];
        $this -> despachante_dni = $objeto['input_dni'];
        $this -> despachante_trabajo = $objeto['input_trabajo'];
        $this -> despachante_direccion = $objeto['input_direccion'];
        $this -> despachante_email = $objeto['input_email'];
        $this -> despachante_fh_alta = date('Y-m-d H:i:s');
        $this -> despachante_baja = 0;

        $id_insert = $this -> insert();
        return ($id_insert);
    }

    function editDespachante($objeto) {
        $this -> despachante_id = $objeto['edit_despachante_id'];
        $this -> find(true);

        $this -> despachante_nombre = $objeto['input_nombre'];
        $this -> despachante_tel1 = $objeto['input_telefono_1'];
        $this -> despachante_observacion = $objeto['input_observacion'];
        $this -> despachante_dni = $objeto['input_dni'];
        $this -> despachante_trabajo = $objeto['input_trabajo'];
        $this -> despachante_direccion = $objeto['input_direccion'];
        $this -> despachante_email = $objeto['input_email'];
        $this -> despachante_fh_alta = date('Y-m-d H:i:s');
        $this -> despachante_baja = 0;

        $this -> update();
        return $this -> despachante_id;
    }

    function eliminarDespachante($objeto) {
        $this -> despachante_id = $objeto['id_eliminar'];
        $this -> find(true);
    
        $this -> despachante_baja = 1;

        $this -> update();
        return $this -> despachante_id;
    }

    function getDespachantes($id=false){
        if($id){
            $this -> despachante_id = $id;
            $this -> find(true);
        } else {
            $this -> despachante_baja = 0;
            $this -> find();
        }

        return $this;
    }

    function getSaldo(){
        $do_cc = DB_DataObject::factory('despachante_cuenta_corriente');
        $do_cc -> ccte_despachante_id = $this -> despachante_id;
        $do_cc -> orderBy('ccte_id DESC');
        $do_cc -> find(true);


        if($do_cc -> ccte_saldo_actual >= 0){
            return '<span class="cc_verde" >'.$do_cc -> ccte_saldo_actual.'</span>';
        }else{
              return '<span class="cc_rojo" >'.$do_cc -> ccte_saldo_actual.'</span>';
        }
    }


    function getSaldoValor(){
        $do_cc = DB_DataObject::factory('despachante_cuenta_corriente');
        $do_cc -> ccte_despachante_id = $this -> despachante_id;
        $do_cc -> orderBy('ccte_id DESC');

        if($do_cc -> find(true)){
            return $do_cc -> ccte_saldo_actual;
        }else{
            return 0;
        }
    }
}

