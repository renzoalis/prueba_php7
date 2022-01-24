<?php
/**
 * Table Definition for conciliacion
 */
require_once 'DB/DataObject.php';

class DataObjects_Conciliacion extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'conciliacion';        // table name
    public $c_id;                            // int(11)  not_null primary_key auto_increment group_by
    public $c_fh;                            // datetime(19)  not_null
    public $c_caja_id;                       // int(11)  not_null group_by
    public $c_usuario_id;                    // int(11)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Conciliacion',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function nuevaConciliacion($id_caja) {
        //Solo dejo las ultimas 3 conciliaciones
        $id_caja_borrar = $id_caja - 3;
        $conciliacion_borrar = DB_DataObject::factory('conciliacion');
        $conciliacion_borrar ->whereAdd('c_caja_id < '.$id_caja_borrar);
        $conciliacion_borrar -> find();

        while($conciliacion_borrar -> fetch()){
            $conciliacion_detalle_borrar = DB_DataObject::factory('conciliacion_detalle');
            $conciliacion_detalle_borrar -> detalle_conc_id =$conciliacion_borrar -> c_id;;
            $conciliacion_detalle_borrar -> find();
            $conciliacion_detalle_borrar -> delete();

            $conciliacion_borrar -> delete();
        }



        $this -> c_fh = date('Y-m-d H:i:s');
        $this -> c_caja_id = $id_caja;
        $this -> c_usuario_id = $_SESSIO['usuario']['id'];

        $id_conc = $this -> insert();

        $producto_stock = DB_DataObject::factory('producto_stock');
        $producto_stock -> whereAdd('ps_cantidad > 0');
        $producto_stock -> find();

        while ($producto_stock -> fetch()){
            //Obtengo la cantidad de productos vendidos no entregados para sumarla
            $venta = DB_DataObject::factory('venta');
            $venta_detalle = DB_DataObject::factory('venta_detalle');
            $devolucion_mercaderia = DB_DataObject::factory('devolucion_mercaderia');
            
            $venta_detalle -> joinAdd($venta,"LEFT");
            $venta_detalle -> joinAdd($devolucion_mercaderia,"LEFT");
            $venta_detalle -> whereAdd('detalle_prod_lote = '.$producto_stock -> ps_id.' AND venta_estado_id IN(1,2)'); //Evaluar si hay que filtrar por fecha de la caja OJOOO
            $venta_detalle -> find();

            while ($venta_detalle -> fetch()) {
                $producto_stock -> cantidad_vendida_sin_retirar += $venta_detalle -> detalle_prod_cant; 
            }

            $cd = DB_DataObject::factory('conciliacion_detalle');
            $cd -> detalle_conc_id = $id_conc;
            $cd -> detalle_prodstock_id = $producto_stock -> ps_id;
            $cd -> detalle_prod_cant_venta = $producto_stock -> ps_cantidad;       //STOCK DE VENTA
            $cd -> detalle_prod_cant_fisico = $producto_stock -> ps_cantidad + $producto_stock -> cantidad_vendida_sin_retirar; //STOCK FISICO
            // $cd -> detalle_tipo_id = $o['tipo'][$k];
            $cd -> detalle_producto_id = $producto_stock -> ps_producto_id;
            $cd -> detalle_calibre = $producto_stock -> ps_calibre;
            $cd -> insert();
        }



        if($id_conc) {
            $caja = DB_DataObject::factory('caja');
            $caja -> caja_id = $id_caja;
            $caja -> find(true);

            $caja -> caja_conciliacion_id = $id_conc;
            $caja -> update();
        }

    }

    function getDatosEnvioServicio(){
        $resp['Info']['puesto'] = PUESTO_ID;
        $resp['Info']['c_id'] = $this -> c_id;
        $resp['Info']['c_fh'] = $this -> c_fh;
        $resp['Info']['c_caja_id'] = $this -> c_caja_id;
        $resp['Info']['c_usuario_id'] = $this -> c_usuario_id;

        $cd = DB_DataObject::factory('conciliacion_detalle');
        $cd -> detalle_conc_id = $this -> c_id;
        $cd -> find();

        while ($cd -> fetch()) {
            $resp['Detalle'][$cd -> detalle_id]['detalle_prodstock_id'] = $cd -> detalle_prodstock_id;
            $resp['Detalle'][$cd -> detalle_id]['detalle_prod_cant_actual'] = $cd -> detalle_prod_cant_actual;
            $resp['Detalle'][$cd -> detalle_id]['detalle_prod_cant_real'] = $cd -> detalle_prod_cant_real;
            $resp['Detalle'][$cd -> detalle_id]['detalle_producto_id'] = $cd -> detalle_producto_id;
            $resp['Detalle'][$cd -> detalle_id]['detalle_calibre'] = $cd -> detalle_calibre;
            $resp['Detalle'][$cd -> detalle_id]['detalle_tipo_id'] = $cd -> detalle_tipo_id;
        }

        return $resp;
    }

}
