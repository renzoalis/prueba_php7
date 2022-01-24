<?php
/**
 * Table Definition for compra_dtv
 */
require_once 'DB/DataObject.php';

class DataObjects_Compra_dtv extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'compra_dtv';          // table name
    public $dtv_id;                          // int(11)  not_null primary_key auto_increment group_by
    public $dtv_fh;                          // datetime(19)  not_null
    public $dtv_estado;                      // tinyint(1)  not_null group_by
    public $dtv_numero;                      // int(11)  not_null group_by
    public $dtv_fh_vencimiento;              // date(10)  
    public $dtv_emisor;                      // varchar(128)  
    public $dtv_tipo;                        // varchar(128)  
    public $dtv_motivo;                      // varchar(128)  
    public $dtv_datos_procedencia;           // varchar(128)  
    public $dtv_datos_destino;               // varchar(128)  
    public $dtv_codigo_cierre;               // varchar(128)  
    public $dtv_producto;                    // varchar(128)  
    public $dtv_variedad;                    // varchar(128)  
    public $dtv_embalaje;                    // varchar(128)  
    public $dtv_cantidad;                    // int(11)  group_by
    public $dtv_peso;                        // int(11)  group_by
    public $dtv_total;                       // int(11)  group_by
    public $dtv_unidad_medida;               // varchar(128)  

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Compra_dtv',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function cargarDtv($objeto){
        $dtv = DB_DataObject::factory('compra_dtv');
        $dtv -> dtv_fh = date("Y-m-d H:i:s");
        $dtv -> dtv_estado = 1; // 1 abierta, 2 cerrada
        $dtv -> dtv_numero = $objeto['input_numero_dtv'];
        $dtv -> dtv_fh_vencimiento = $objeto['input_fecha_vencimiento_dtv'];
        $dtv -> dtv_emisor = $objeto['emisor_dtv'];
        $dtv -> dtv_tipo = $objeto['input_tipo_dtv'];
        $dtv -> dtv_motivo = $objeto['input_motivo_dtv'];
        $dtv -> dtv_datos_procedencia = $objeto['input_procedencia_dtv'];
        $dtv -> dtv_datos_destino = $objeto['input_destino_dtv'];
        $dtv -> dtv_codigo_cierre = $objeto['input_codcierre_dtv'];
        $dtv -> dtv_producto = $objeto['input_producto_dtv'];
        $dtv -> dtv_variedad = $objeto['input_variedad_dtv'];
        $dtv -> dtv_embalaje = $objeto['input_embalaje_dtv'];
        $dtv -> dtv_cantidad = $objeto['input_cantidad_dtv'];
        $dtv -> dtv_peso = $objeto['input_pesocantidad_dtv'];
        $dtv -> dtv_total = $objeto['input_total_dtv'];
        $dtv -> dtv_unidad_medida = $objeto['input_unidadmedida_dtv'];
        $id_dtv = $dtv -> insert();
        return $id_dtv;
    }
}
