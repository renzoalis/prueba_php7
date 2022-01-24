<?php
/**
 * Table Definition for forma_pago
 */
require_once 'DB/DataObject.php';

class DataObjects_Forma_pago extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'forma_pago';          // table name
    public $fp_id;                           // int(11)  not_null primary_key auto_increment group_by
    public $fp_desc;                         // varchar(45)  not_null
    public $fp_baja;                         // int(1)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Forma_pago',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
