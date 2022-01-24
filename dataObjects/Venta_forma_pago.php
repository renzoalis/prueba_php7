<?php
/**
 * Table Definition for venta_forma_pago
 */
require_once 'DB/DataObject.php';

class DataObjects_Venta_forma_pago extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'venta_forma_pago';    // table name
    public $vf_id;                           // int(11)  not_null primary_key auto_increment group_by
    public $vf_desc;                         // varchar(45)  not_null
    public $vf_baja;                         // int(1)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Venta_forma_pago',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
