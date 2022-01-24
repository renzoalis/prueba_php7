<?php
/**
 * Table Definition for compra_detalle
 */
require_once 'DB/DataObject.php';

class DataObjects_Compra_detalle extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'compra_detalle';      // table name
    public $detalle_id;                      // int(11)  not_null primary_key auto_increment group_by
    public $detalle_compra_id;               // int(11)  not_null group_by
    public $detalle_prod_id;                 // int(11)  not_null group_by
    public $detalle_prod_cant;               // int(11)  not_null group_by
    public $detalle_prod_precio_u;           // float(11)  not_null group_by
    public $detalle_prod_calibre;            // varchar(256)  
    public $detalle_prod_dev;                // int(11)  group_by
    public $detalle_ps_id;                   // int(11)  group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Compra_detalle',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
