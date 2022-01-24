<?php
/**
 * Table Definition for dtv_producto
 */
require_once 'DB/DataObject.php';

class DataObjects_Dtv_producto extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'dtv_producto';        // table name
    public $dtv_producto_id;                 // int(11)  not_null primary_key auto_increment group_by
    public $dtv_id;                          // int(11)  not_null group_by
    public $dtv_producto;                    // varchar(512)  
    public $dtv_variedad;                    // varchar(512)  
    public $dtv_embalaje;                    // varchar(512)  
    public $dtv_peso;                        // int(11)  group_by
    public $dtv_cantidad;                    // int(11)  group_by
    public $dtv_total;                       // int(11)  group_by
    public $dtv_unidad_medida;               // varchar(512)  

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Dtv_producto',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function cargarProducto($idDTV,$productos){
        foreach ($productos as $p) {
            $dtv_producto = DB_DataObject::factory('dtv_producto'); 
            $dtv_producto -> dtv_id = $idDTV;
            $dtv_producto -> dtv_producto = $p['producto'];
            $dtv_producto -> dtv_variedad = $p['variedad'];
            $dtv_producto -> dtv_embalaje = $p['tipo_embalaje'];
            $dtv_producto -> dtv_peso = $p['peso'];
            $dtv_producto -> dtv_cantidad = $p['cantidad'];
            $dtv_producto -> dtv_total = $p['total'];
            $dtv_producto -> dtv_unidad_medida = $p['tipo_umedida'];
            $dtv_producto -> insert();
            }
        }
    


}
