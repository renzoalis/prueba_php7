<?php
/**
 * Table Definition for exportador
 */
require_once 'DB/DataObject.php';

class DataObjects_Exportador extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'exportador';          // table name
    public $exportador_id;                   // int(11)  not_null primary_key auto_increment group_by
    public $exportador_nombre;               // varchar(256)  not_null
    public $exportador_email;                // varchar(256)  
    public $exportador_tel1;                 // varchar(128)  
    public $exportador_emp_nombre;           // varchar(128)  
    public $exportador_dni;                  // varchar(128)  
    public $exportador_observacion;          // blob(65535)  blob
    public $exportador_direccion;            // varchar(256)  
    public $exportador_fh_alta;              // datetime(19)  not_null
    public $exportador_baja;                 // tinyint(1)  not_null unsigned zerofill group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Exportador',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    function nuevoExportador($objeto) {
        $do_exportador = DB_DataObject::factory('exportador');
        $do_exportador -> exportador_nombre = $objeto['input_nombre'];
        $do_exportador -> exportador_tel1 = $objeto['input_telefono_1'];
        $do_exportador -> exportador_observacion = $objeto['input_observacion'];
        $do_exportador -> exportador_dni = $objeto['input_dni'];
        $do_exportador -> exportador_emp_nombre = $objeto['input_empresa'];
        $do_exportador -> exportador_direccion = $objeto['input_direccion'];
        $do_exportador -> exportador_email = $objeto['input_email'];
        $do_exportador -> exportador_fh_alta = date('Y-m-d H:i:s');
        $do_exportador -> exportador_baja = 0;

        $id_insert = $do_exportador -> insert();
        return $id_insert;
    }

    function editExportador($objeto) {
        $this -> exportador_id = $objeto['edit_exportador_id'];
        $this -> find(true);

        $this -> exportador_nombre = $objeto['input_nombre'];
        $this -> exportador_tel1 = $objeto['input_telefono_1'];
        $this -> exportador_observacion = $objeto['input_observacion'];
        $this -> exportador_dni = $objeto['input_dni'];
        $this -> exportador_emp_nombre = $objeto['input_empresa'];
        $this -> exportador_direccion = $objeto['input_direccion'];
        $this -> exportador_email = $objeto['input_email'];
        $this -> exportador_fh_alta = date('Y-m-d H:i:s');
        $this -> exportador_baja = 0;

        $this -> update();
        return $this -> exportador_id;
    }

    function eliminarExportador($objeto) {
        $this -> exportador_id = $objeto['id_eliminar'];
        $this -> find(true);
    
        $this -> exportador_baja = 1;

        $this -> update();
        return $this -> exportador_id;
    }

    function getExportadores($id=false){
        if($id){
            $this -> exportador_id = $id;
            $this -> find(true);
        } else {
            $this -> exportador_baja = 0;
            $this -> find();
        }

        return $this;
    }

    function getSaldo(){
        $do_cc = DB_DataObject::factory('exportador_cuenta_corriente');
        $do_cc -> ccte_exportador_id = $this -> exportador_id;
        $do_cc -> orderBy('ccte_id DESC');
        $do_cc -> find(true);

        if($do_cc -> ccte_saldo_actual >= 0){
            return '<span class="cc_verde" >'.$do_cc -> ccte_saldo_actual.'</span>';
        }else{
              return '<span class="cc_rojo" >'.$do_cc -> ccte_saldo_actual.'</span>';
        }
    }   
}
