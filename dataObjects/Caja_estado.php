<?php
/**
 * Table Definition for caja_estado
 */
require_once 'DB/DataObject.php';

class DataObjects_Caja_estado extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'caja_estado';         // table name
    public $ce_id;                           // int(11)  not_null primary_key auto_increment group_by
    public $ce_nombre;                       // varchar(256)  not_null
    public $ce_baja;                         // int(1)  group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Caja_estado',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
