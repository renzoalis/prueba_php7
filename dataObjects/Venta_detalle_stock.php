<?php
/**
 * Table Definition for venta_detalle_stock
 */
require_once 'DB/DataObject.php';

class DataObjects_Venta_detalle_stock extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'venta_detalle_stock';    // table name
    public $vds_id;                          // int(11)  not_null primary_key auto_increment group_by
    public $vds_venta_detalle_id;            // int(11)  group_by
    public $vds_prodstock_id;                // int(11)  group_by
    public $vds_prod_cant;                   // int(11)  not_null group_by
    public $vds_cant_dev;                    // int(11)  group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Venta_detalle_stock',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
