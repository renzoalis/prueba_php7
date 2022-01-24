<?php
/**
 * Table Definition for costo_mercaderia
 */
require_once 'DB/DataObject.php';

class DataObjects_Costo_mercaderia extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'costo_mercaderia';    // table name
    public $cm_id;                           // int(11)  not_null primary_key auto_increment group_by
    public $cm_fh;                           // datetime(19)  not_null
    public $cm_compra_id;                    // int(11)  not_null group_by
    public $cm_usuario_id;                   // int(11)  not_null group_by
    public $cm_observacion;                  // varchar(256)  not_null
    public $cm_comprob;                      // varchar(128)  

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Costo_mercaderia',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function nuevo_costo_mercaderia($objeto) {
        $this -> cm_fh = date("Y-m-d H:i:s");
        if($objeto['compra_id']){
            $this -> cm_compra_id = $objeto['compra_id'];
        }
        // $this -> cm_costo_unitario = $objeto['input_costo_unitario']; 
        // $this -> cm_cantidad = $objeto['input_cantidad']; 
        $this -> cm_monto_total = $objeto['input_monto']; 
        $this -> cm_usuario_id = $_SESSION['usuario']['id'];
        $this -> cm_observacion = $objeto['input_obs_concepto'];
        $this -> cm_comprob = $objeto['input_comprob'];
        
        $id_cm = $this -> insert();
        
       
        // Genero asiento en CC de Proveedor
        $proveedor_consulta = DB_DataObject::factory('proveedor_cuenta_corriente');
        $ultima_cc = $proveedor_consulta -> getUltimaCC($objeto['input_prov_id']);


        $proveedor_cc = DB_DataObject::factory('proveedor_cuenta_corriente');
        $proveedor_cc -> ccte_proveedor_id = $objeto['input_prov_id'];
        $proveedor_cc -> ccte_fh = date('Y-m-d H:i:s');
        $proveedor_cc -> ccte_operacion_tipo = 15;       //costo de mercaderia
        $proveedor_cc -> ccte_operacion_id = $id_cm;
        $proveedor_cc -> ccte_importe = $objeto['input_monto'];
        $proveedor_cc -> ccte_saldo_actual = $ultima_cc -> ccte_saldo_actual -$objeto['input_monto'];
        $proveedor_cc -> insert(); 



        return $id_cm;
    }
}
