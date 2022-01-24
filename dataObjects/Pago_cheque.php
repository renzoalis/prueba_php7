<?php
/**
 * Table Definition for pago_cheque
 */
require_once 'DB/DataObject.php';

class DataObjects_Pago_cheque extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'pago_cheque';         // table name
    public $pc_id;                           // int(11)  not_null primary_key group_by
    public $pc_cheque_numero;                // int(11)  not_null group_by
    public $pc_monto;                        // float(11)  not_null group_by
    public $pc_vencimiento;                  // date(10)  not_null

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Pago_cheque',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
