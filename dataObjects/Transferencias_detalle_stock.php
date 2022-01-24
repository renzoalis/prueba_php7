<?php
/**
 * Table Definition for transferencias_detalle_stock
 */
require_once 'DB/DataObject.php';

class DataObjects_Transferencias_detalle_stock extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'transferencias_detalle_stock';    // table name
    public $tds_id;                          // int(11)  not_null primary_key auto_increment group_by
    public $tds_detalle_id;                  // int(11)  group_by
    public $tds_prodstock_id;                // int(11)  group_by
    public $tds_prod_cant;                   // int(11)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Transferencias_detalle_stock',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
