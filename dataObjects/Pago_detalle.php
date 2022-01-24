<?php
/**
 * Table Definition for pago_detalle
 */
require_once 'DB/DataObject.php';

class DataObjects_Pago_detalle extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'pago_detalle';        // table name
    public $detalle_id;                      // int(11)  not_null primary_key auto_increment group_by
    public $detalle_pago_id;                 // int(11)  not_null group_by
    public $detalle_ccop_tipo;               // int(11)  not_null group_by
    public $detalle_ccop_id;                 // int(11)  not_null group_by
    public $detalle_monto;                   // float(11)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Pago_detalle',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
