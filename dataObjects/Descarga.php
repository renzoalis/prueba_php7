<?php
/**
 * Table Definition for descarga
 */
require_once 'DB/DataObject.php';

class DataObjects_Descarga extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'descarga';            // table name
    public $desc_id;                         // int(11)  not_null primary_key auto_increment group_by
    public $desc_fh;                         // date(10)  not_null
    public $desc_compra_id;                  // int(11)  group_by
    public $desc_venta_id;                   // int(11)  group_by
    public $desc_transf_id;                  // int(11)  group_by
    public $desc_monto_total;                // float(11)  not_null group_by
    public $desc_usuario_id;                 // int(11)  not_null group_by
    public $desc_observacion;                // varchar(256)  
    public $desc_comprob;                    // varchar(128)  
    public $desc_cantidad;                   // int(11)  group_by
    public $desc_costo_unitario;             // float(11)  group_by
    public $desc_descripcion;                // varchar(128)  

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Descarga',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE


     function nuevaDescarga($objeto) {
        $this -> desc_fh = date("Y-m-d H:i:s");
        
        if($objeto['compra_id']){
            $this -> desc_compra_id = $objeto['compra_id'];
        }

        if($objeto['transf_id']){
            $this -> desc_transf_id = $objeto['transf_id'];
        }
        
        $this -> desc_monto_total = $objeto['input_monto']; 
        $this -> desc_usuario_id = $_SESSION['usuario']['id'];
        $this -> desc_observacion = $objeto['input_obs_concepto'];
        $this -> desc_comprob = $objeto['input_comprob'];
        $this -> desc_descripcion = $objeto['input_obs_devolver'];
        
        $id_desc = $this -> insert();
        
        // Genero asiento en CC de descarga
        $cardesc_consulta = DB_DataObject::factory('cardesc_cuenta_corriente');
        $ultima_cc = $cardesc_consulta -> getUltimaCC(2);


        $cardesc_cc = DB_DataObject::factory('cardesc_cuenta_corriente');
        $cardesc_cc -> ccte_cardesc_id = 2;
        $cardesc_cc -> ccte_fh = date('Y-m-d H:i:s');
        $cardesc_cc -> ccte_operacion_tipo = 13;       //DESCARGA
        $cardesc_cc -> ccte_operacion_id = $id_desc;
        $cardesc_cc -> ccte_importe = $objeto['input_monto'];
        $cardesc_cc -> ccte_saldo_actual = $ultima_cc -> ccte_saldo_actual -$objeto['input_monto'];
        $cardesc_cc -> insert(); 

        return $id_desc;
    }
   function nuevaDescargaVenta($objeto) {

        $vd = DB_DataObject::factory('venta_detalle');
        $vd -> whereAdd('detalle_id = '.$objeto['concepto_venta_detalle_id']);
        $vd -> find(true);
        $this -> desc_fh = date("Y-m-d H:i:s");
        
        if($objeto['concepto_venta_id']){
            $this -> desc_venta_id = $objeto['concepto_venta_id'];
        }
        
        $this -> desc_costo_unitario = $objeto['input_monto'] / $vd -> detalle_prod_cant; 
        $this -> desc_cantidad = $vd -> detalle_prod_cant; 
        $this -> desc_monto_total = $objeto['input_monto']; 
        $this -> desc_usuario_id = $_SESSION['usuario']['id'];
        $this -> desc_observacion = $objeto['input_obs_concepto'];
        $this -> desc_comprob = $objeto['input_comprob'];
        $this -> desc_descripcion = $objeto['input_obs_devolver'];
        
        $id_desc = $this -> insert();
        

        return $id_desc;
    }

    function getCostoDescarga($ps_id){

        $do_descarga_detalle = DB_DataObject::factory('descarga_detalle');

        $do_descarga_detalle -> detalle_ps_id = $ps_id;
        $do_descarga_detalle -> find();

        while ($do_descarga_detalle -> fetch()) {
            $costo_u += $do_descarga_detalle -> detalle_descarga_costo * $do_descarga_detalle -> detalle_descarga_cant;
            $cantidad += $do_descarga_detalle -> detalle_descarga_cant;
        }

        if($cantidad){
            return round($costo_u/$cantidad,2);
        }else{
            return 0;
        }
    }
}
