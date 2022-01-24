<?php
/**
 * Table Definition for flete
 */
require_once 'DB/DataObject.php';

class DataObjects_Flete extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'flete';               // table name
    public $flete_id;                        // int(11)  not_null primary_key auto_increment group_by
    public $flete_fh;                        // date(10)  not_null
    public $flete_transp_id;                 // int(11)  not_null group_by
    public $flete_compra_id;                 // int(11)  group_by
    public $flete_monto_total;               // float(11)  not_null group_by
    public $flete_usuario_id;                // int(11)  not_null group_by
    public $flete_observacion;               // varchar(256)  
    public $flete_comprob;                   // varchar(128)  
    public $flete_cantidad;                  // int(11)  group_by
    public $flete_costo_unitario;            // float(11)  group_by
    public $flete_transf_id;                 // int(11)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Flete',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function nuevoFlete($objeto) {
        $this -> flete_fh = date("Y-m-d H:i:s");
        $this -> flete_transp_id = $objeto['input_id_transp'];
        if($objeto['compra_id']){
            $this -> flete_compra_id = $objeto['compra_id'];
        }

        if($objeto['transf_id']){
            $this -> flete_transf_id = $objeto['transf_id'];
        }
       // $this -> flete_costo_unitario = $objeto['input_monto_total'] / $cd -> detalle_prod_cant; 
       // $this -> flete_cantidad = $cd -> detalle_prod_cant; 
        $this -> flete_monto_total = $objeto['input_monto_total']; 
        $this -> flete_usuario_id = $_SESSION['usuario']['id'];
        $this -> flete_observacion = $objeto['input_obs_concepto'];
        $this -> flete_comprob = $objeto['input_comprob'];
        
        $id_flete = $this -> insert();

        $cc = DB_DataObject::factory('transportista_cuenta_corriente');
        $id_cc = $cc -> cargarflete($objeto, $id_flete);

        if($id_cc) {
            return $id_flete;
        } else {
            return "ERROR CC";
        }
    }


     function getCostoFlete($ps_id){

        $do_flete_detalle = DB_DataObject::factory('flete_detalle');

        $do_flete_detalle -> detalle_ps_id = $ps_id;
        $do_flete_detalle -> find();

        while ($do_flete_detalle -> fetch()) {
            $costo_u += $do_flete_detalle -> desc_costo_unitario * $do_flete_detalle -> desc_cantidad;
            $cantidad += $do_flete_detalle -> desc_cantidad;
        }

        if($cantidad){
            return round($costo_u/$cantidad,2);
        }else{
            return 0;
        }
    }


}
