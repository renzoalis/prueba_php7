<?php
/**
 * Table Definition for dtv
 */
require_once 'DB/DataObject.php';

class DataObjects_Dtv extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'dtv';                 // table name
    public $dtv_id;                          // int(11)  not_null primary_key auto_increment group_by
    public $dtv_fh;                          // datetime(19)  not_null
    public $dtv_estado;                      // tinyint(1)  not_null group_by
    public $dtv_numero;                      // int(32)  not_null group_by
    public $dtv_fh_vencimiento;              // date(10)  
    public $dtv_emisor;                      // varchar(512)  
    public $dtv_cuitemisor;                  // int(32)  group_by
    public $dtv_tipo;                        // varchar(512)  
    public $dtv_motivo;                      // varchar(512)  
    public $dtv_receptor;                    // varchar(512)  
    public $dtv_cuitreceptor;                // int(32)  group_by
    public $dtv_codigo_cierre;               // varchar(512)  
    public $dtv_domicilio;                   // varchar(512)  

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Dtv',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    function cargarDtv($objeto){
        $dtv = DB_DataObject::factory('dtv');
        $dtv -> dtv_fh = $objeto['dtv_fh'];;
        $dtv -> dtv_estado = 1; // 1 abierta, 2 cerrada
        $dtv -> dtv_numero = $objeto['input_numero_dtv'];
        $dtv -> dtv_fh_vencimiento = $objeto['input_fecha_vencimiento_dtv'];
        $dtv -> dtv_emisor = $objeto['emisor_dtv'];
        $dtv -> dtv_cuitemisor = $objeto['input_cuitemisor_dtv'];
        $dtv -> dtv_tipo = $objeto['input_tipo_dtv'];
        $dtv -> dtv_motivo = $objeto['input_motivo_dtv'];
        $dtv -> dtv_receptor = $objeto['input_receptor_dtv'];
        $dtv -> dtv_cuitreceptor = $objeto['input_cuitreceptor_dtv'];
        $dtv -> dtv_codigo_cierre = $objeto['input_codcierre_dtv'];
        $dtv -> dtv_domicilio = $objeto['input_domicilio_dtv'];
        $id_dtv = $dtv -> insert();
    
        $dtv_producto = DB_DataObject::factory('dtv_producto');
        $dtv_producto -> cargarProducto($id_dtv,$objeto['dtv_prod']);

        return $id_dtv;
    }

    function getDtVs($id=false){
        if($id){
            $this -> dtv_id = $id;
            $this -> find(true);
        } else {
            $this -> find();
        }

        return $this;
    }

    function getDtVsPorEstado($estado){
        $this -> dtv_estado = $estado;
        $this -> find();
        return $this;
    }

    function getDatosEnvioServicio(){

        $this -> whereAdd('dtv_estado = 1');
        $this -> find();

        while ($this -> fetch()) {
            $resp[$this -> dtv_id]['Info']['puesto'] = PUESTO_ID;
            $resp[$this -> dtv_id]['Info']['dtv_id'] = $this -> dtv_id;
            $resp[$this -> dtv_id]['Info']['dtv_numero'] = $this -> dtv_numero;
            $resp[$this -> dtv_id]['Info']['dtv_fh_vencimiento'] = $this -> dtv_fh_vencimiento;
            $resp[$this -> dtv_id]['Info']['dtv_emisor'] = $this -> dtv_emisor;
            $resp[$this -> dtv_id]['Info']['dtv_cuitemisor'] = $this -> dtv_cuitemisor;
            $resp[$this -> dtv_id]['Info']['dtv_tipo'] = $this -> dtv_tipo;
            $resp[$this -> dtv_id]['Info']['dtv_motivo'] = $this -> dtv_motivo;
            $resp[$this -> dtv_id]['Info']['dtv_receptor'] = $this -> dtv_receptor;
            $resp[$this -> dtv_id]['Info']['dtv_cuitreceptor'] = $this -> dtv_cuitreceptor;
            $resp[$this -> dtv_id]['Info']['dtv_codigo_cierre'] = $this -> dtv_codigo_cierre;
            $resp[$this -> dtv_id]['Info']['dtv_domicilio'] = $this -> dtv_domicilio;

            $cd = DB_DataObject::factory('dtv_producto');
            $cd -> dtv_id = $this -> dtv_id;
            $cd -> find();

            while ($cd -> fetch()) {
                $resp[$this -> dtv_id]['Detalle'][$cd -> dtv_producto_id]['producto'] = $cd -> dtv_producto;
                $resp[$this -> dtv_id]['Detalle'][$cd -> dtv_producto_id]['variedad'] = $cd -> dtv_variedad;
                $resp[$this -> dtv_id]['Detalle'][$cd -> dtv_producto_id]['tipo_embalaje'] = $cd -> dtv_embalaje;
                $resp[$this -> dtv_id]['Detalle'][$cd -> dtv_producto_id]['peso'] = $cd -> dtv_peso;
                $resp[$this -> dtv_id]['Detalle'][$cd -> dtv_producto_id]['cantidad'] = $cd -> dtv_cantidad;
                $resp[$this -> dtv_id]['Detalle'][$cd -> dtv_producto_id]['total'] = $cd -> dtv_total;
                $resp[$this -> dtv_id]['Detalle'][$cd -> dtv_producto_id]['tipo_umedida'] = $cd -> dtv_unidad_medida;
            }
            
        }

        return $resp;
    }

}
