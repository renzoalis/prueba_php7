<?php
/**
 * Table Definition for importador
 */
require_once 'DB/DataObject.php';

class DataObjects_Importador extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'importador';          // table name
    public $importador_id;                   // int(11)  not_null primary_key auto_increment group_by
    public $importador_nombre;               // varchar(256)  not_null
    public $importador_email;                // varchar(256)  
    public $importador_tel1;                 // varchar(128)  
    public $importador_emp_nombre;           // varchar(128)  
    public $importador_dni;                  // varchar(128)  
    public $importador_observacion;          // blob(65535)  blob
    public $importador_direccion;            // varchar(256)  
    public $importador_fh_alta;              // datetime(19)  not_null
    public $importador_baja;                 // tinyint(1)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Importador',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    function nuevoImportador($objeto) {
        $do_importador = DB_DataObject::factory('importador');
        $do_importador -> importador_nombre = $objeto['input_nombre'];
        $do_importador -> importador_tel1 = $objeto['input_telefono_1'];
        $do_importador -> importador_observacion = $objeto['input_observacion'];
        $do_importador -> importador_dni = $objeto['input_dni'];
        $do_importador -> importador_emp_nombre = $objeto['input_empresa'];
        $do_importador -> importador_direccion = $objeto['input_direccion'];
        $do_importador -> importador_email = $objeto['input_email'];
        $do_importador -> importador_fh_alta = date('Y-m-d H:i:s');
        $do_importador -> importador_baja = 0;

        $id_insert = $do_importador -> insert();
        return $id_insert;
    }

    function editImportador($objeto) {
        $this -> importador_id = $objeto['edit_importador_id'];
        $this -> find(true);

        $this -> importador_nombre = $objeto['input_nombre'];
        $this -> importador_tel1 = $objeto['input_telefono_1'];
        $this -> importador_observacion = $objeto['input_observacion'];
        $this -> importador_dni = $objeto['input_dni'];
        $this -> importador_emp_nombre = $objeto['input_empresa'];
        $this -> importador_direccion = $objeto['input_direccion'];
        $this -> importador_email = $objeto['input_email'];
        $this -> importador_fh_alta = date('Y-m-d H:i:s');
        $this -> importador_baja = 0;

        $this -> update();
        return $this -> importador_id;
    }

    function eliminarImportador($objeto) {
        $this -> importador_id = $objeto['id_eliminar'];
        $this -> find(true);
    
        $this -> importador_baja = 1;

        $this -> update();
        return $this -> importador_id;
    }

    function getImportadores($id=false){
        if($id){
            $this -> importador_id = $id;
            $this -> find(true);
        } else {
            $this -> importador_baja = 0;
            $this -> find();
        }

        return $this;
    }

    function getSaldo(){
        $do_cc = DB_DataObject::factory('importador_cuenta_corriente');
        $do_cc -> ccte_importador_id = $this -> importador_id;
        $do_cc -> orderBy('ccte_id DESC');
        $do_cc -> find(true);

        if($do_cc -> ccte_saldo_actual >= 0){
            return '<span class="cc_verde" >'.$do_cc -> ccte_saldo_actual.'</span>';
        }else{
              return '<span class="cc_rojo" >'.$do_cc -> ccte_saldo_actual.'</span>';
        }
    }   
}
