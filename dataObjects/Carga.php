<?php
/**
 * Table Definition for carga
 */
require_once 'DB/DataObject.php';

class DataObjects_Carga extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'carga';               // table name
    public $carga_id;                        // int(11)  not_null primary_key auto_increment group_by
    public $carga_fh;                        // date(10)  not_null
    public $carga_compra_id;                 // int(11)  group_by
    public $carga_transf_id;                 // int(11)  group_by
    public $carga_monto_total;               // float(11)  not_null group_by
    public $carga_usuario_id;                // int(11)  not_null group_by
    public $carga_observacion;               // varchar(256)  
    public $carga_comprob;                   // varchar(128)  
    public $carga_cantidad;                  // int(11)  group_by
    public $carga_costo_unitario;            // float(11)  group_by
    public $carga_venta_id;                  // int(11)  group_by
    public $carga_descripcion;               // varchar(128)  

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Carga',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function nuevaCarga($objeto) {
        $this -> carga_fh = date("Y-m-d H:i:s");
        $this -> carga_transp_id = $objeto['input_id_transp'];
        if($objeto['compra_id']){
            $this -> carga_compra_id = $objeto['compra_id'];
        }
        if($objeto['transf_id']){
            $this -> carga_transf_id = $objeto['transf_id'];
        }
        $this -> carga_costo_unitario = $objeto['input_costo_unitario']; 
        $this -> carga_cantidad = $objeto['input_cantidad']; 
        $this -> carga_monto_total = $objeto['input_monto']; 
        $this -> carga_usuario_id = $_SESSION['usuario']['id'];
        $this -> carga_observacion = $objeto['input_obs_concepto'];

        
        $id_carga = $this -> insert();
       
        // Genero asiento en CC de descarga
        $cardesc_consulta = DB_DataObject::factory('cardesc_cuenta_corriente');
        $ultima_cc = $cardesc_consulta -> getUltimaCC(1);

        $cardesc_cc = DB_DataObject::factory('cardesc_cuenta_corriente');
        $cardesc_cc -> ccte_cardesc_id = 1;
        $cardesc_cc -> ccte_fh = date('Y-m-d H:i:s');
        $cardesc_cc -> ccte_operacion_tipo = 14;       //Carga
        $cardesc_cc -> ccte_operacion_id = $id_carga;
        $cardesc_cc -> ccte_importe = $objeto['input_monto'];
        $cardesc_cc -> ccte_saldo_actual = $ultima_cc -> ccte_saldo_actual -$objeto['input_monto'];
        $cardesc_cc -> insert(); 

        return $id_carga;
    }

    function nuevaCargaVenta($objeto) {
        $vd = DB_DataObject::factory('venta_detalle');
        $vd -> whereAdd('detalle_id = '.$objeto['concepto_venta_detalle_id']);
        $vd -> find(true);
        $this -> carga_fh = date("Y-m-d H:i:s");
        if($objeto['concepto_venta_id']){
            $this -> carga_venta_id = $objeto['concepto_venta_detalle_id'];
        }
        $this -> carga_costo_unitario = objeto['input_monto'] / $vd -> detalle_prod_cant;
        $this -> carga_cantidad = $vd -> detalle_prod_cant; 
        $this -> carga_monto_total = $objeto['input_monto']; 
        $this -> carga_usuario_id = $_SESSION['usuario']['id'];
        $this -> carga_observacion = $objeto['input_obs_concepto'];
        $this -> carga_descripcion = $objeto['input_obs_carga'];
        $this -> carga_comprob = $objeto['input_comprob'];
        // $this -> carga_ps_id = $vd -> detalle_prod_lote;;

        
        $id_carga = $this -> insert();

        return $id_carga;
    }

    function getCostoCarga($ps_id){


        $do_carga_detalle = DB_DataObject::factory('carga_detalle');

        $do_carga_detalle -> detalle_ps_id = $ps_id;
        $do_carga_detalle -> find();

        while ($do_carga_detalle -> fetch()) {
            $costo_u += $do_carga_detalle -> detalle_carga_costo * $do_carga_detalle -> detalle_carga_cant;
            $cantidad += $do_carga_detalle -> detalle_carga_cant;
        }

        if($cantidad){
            return round($costo_u/$cantidad,2);
        }else{
            return 0;
        }
    }
}
