<?php
/**
 * Table Definition for venta_concepto
 */
require_once 'DB/DataObject.php';

class DataObjects_Venta_concepto extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'venta_concepto';      // table name
    public $vc_id;                           // int(11)  not_null primary_key auto_increment group_by
    public $vc_venta_id;                     // int(11)  not_null group_by
    public $vc_venta_detalle_id;             // int(11)  group_by
    public $vc_tipo;                         // int(11)  not_null group_by
    public $vc_observacion;                  // varchar(256)  
    public $vc_fh;                           // datetime(19)  
    public $vc_monto;                        // int(11)  group_by
    public $vc_op_id;                        // int(11)  group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Venta_concepto',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function nuevoConcepto($objeto) {
        $this -> vc_venta_id = $objeto['concepto_venta_id'];
        $this -> vc_tipo = $objeto['tipo_concepto'];
        $this -> vc_fh = date('Y-m-d H:i:s');
        $this -> vc_monto = $objeto['input_monto'];
        $this -> vc_observacion = $objeto['input_obs_concepto'];

        $venta = DB_DataObject::factory('venta');
        $venta -> venta_id = $objeto['concepto_venta_id'];
        $venta -> find(true);
        if($objeto['tipo_concepto'] == 5){                                         // DEVOLUCION MERCADERIA
            $dev_mercaderia = DB_DataObject::factory('devolucion_mercaderia');
            $nc_dev = $dev_mercaderia -> devolverMercaderiaVenta($objeto);
        }elseif($objeto['tipo_concepto'] == 6){                                    // CARGA 
            $carga_mercaderia = DB_DataObject::factory('carga');
            $nc_dev = $carga_mercaderia -> nuevaCargaVenta($objeto);
        }elseif($objeto['tipo_concepto'] == 7){                                    // DESCARGA
            $descarga_mercaderia = DB_DataObject::factory('descarga');
            $nc_dev = $descarga_mercaderia -> nuevaDescargaVenta($objeto);
        }
        $this -> vc_op_id = $nc_dev;     //Guardo el Id de la operacion
        $id = $this -> insert();
    }

    function getTipo(){
         $do_tipo = DB_DataObject::factory('venta_concepto_tipo');
         $do_tipo -> vc_tipo_id = $this -> vc_tipo;
         $do_tipo -> find(true);
        
         if($this -> vc_tipo == 5){
             $dev_mercaderia = DB_DataObject::factory('devolucion_mercaderia');
             $dev_mercaderia -> dev_id = $this -> vc_op_id;
             $dev_mercaderia -> find(true);
             $texto = ' ('.$dev_mercaderia -> dev_cantidad.' x '.$dev_mercaderia -> dev_obs.')';
         }elseif($this -> vc_tipo == 7){
             $descarga = DB_DataObject::factory('descarga');
             $descarga -> desc_id = $this -> vc_op_id;
             $descarga -> find(true);
             $texto = ' ('.$descarga -> desc_cantidad.' x '.$descarga -> desc_descripcion.') COMP. '.$descarga -> desc_comprob;
         }elseif($this -> vc_tipo == 6){
             $carga = DB_DataObject::factory('carga');
             $carga -> carga_id = $this -> vc_op_id;
             $carga -> find(true);
             $texto = ' ('.$carga -> carga_cantidad.' x '.$carga -> carga_descripcion.') COMP. '.$carga -> carga_comprob;
         }else{
            $texto = '';
         }
         
         return $do_tipo -> vc_tipo_nombre.$texto;
    }

    function nuevoDescuento($o){
        //DB_DataObject::debugLevel(1);
        $venta_detalle = DB_DataObject::factory('venta_detalle');
        $venta_detalle -> detalle_id = $o['id_detalle'];
        $venta_detalle -> find(true);

        $venta_detalle -> detalle_descuento_parcial = $venta_detalle -> detalle_descuento_parcial + $o['desc_num'];
        $venta_detalle -> update();

        $venta = DB_DataObject::factory('venta');
        $venta -> venta_id = $o['desc_venta_id'];
        $venta -> find(true);
        
        $venta -> venta_descuento_total = $venta -> venta_descuento_total + $o['desc_num'];
        $venta -> update();

        $this -> vc_venta_id = $o['desc_venta_id'];
        $this -> vc_venta_detalle_id = $o['id_detalle'];
        $this -> vc_tipo = 9;       //DESCUENTO PARCIAL
        $this -> vc_observacion = $o['nombre_prod'];
        $this -> vc_fh = date('Y-m-d H:i:s');
        $this -> vc_monto = $o['desc_num'];

        $id = $this -> insert();

        // //Cargo la nota de credito al cliente
        // $objeto['input_id_cliente'] = $o['desc_cliente'];
        // $objeto['combo_tipo'] = 'NC';
        // $objeto['input_monto'] = $o['desc_num'];
        // $objeto['nota_fh'] = date('Y-m-d H:i:s');
        // $objeto['input_obs_nota'] = "Descuento en venta: ".$o['desc_venta_id'];

        // $nota = DB_DataObject::factory('notas');
        // $id_nota = $nota -> nuevaNota($objeto);


        return $id;
    }


}
