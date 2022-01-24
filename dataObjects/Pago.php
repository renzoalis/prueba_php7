<?php
/**
 * Table Definition for pago
 */
require_once 'DB/DataObject.php';

class DataObjects_Pago extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'pago';                // table name
    public $pago_id;                         // int(11)  not_null primary_key auto_increment group_by
    public $pago_fh;                         // datetime(19)  not_null
    public $pago_cliente_id;                 // int(11)  not_null group_by
    public $pago_monto_total;                // float(11)  not_null group_by
    public $pago_usuario_id;                 // int(11)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Pago',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
