<?php
/**
 * Table Definition for conciliacion_detalle
 */
require_once 'DB/DataObject.php';

class DataObjects_Conciliacion_detalle extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'conciliacion_detalle';    // table name
    public $detalle_id;                      // int(11)  not_null primary_key auto_increment group_by
    public $detalle_conc_id;                 // int(11)  not_null group_by
    public $detalle_prodstock_id;            // int(11)  not_null group_by
    public $detalle_prod_cant_venta;         // int(11)  not_null group_by
    public $detalle_prod_cant_fisico;        // int(11)  not_null group_by
    public $detalle_tipo_id;                 // int(11)  group_by
    public $detalle_producto_id;             // int(11)  group_by
    public $detalle_calibre;                 // varchar(256)  

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Conciliacion_detalle',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
