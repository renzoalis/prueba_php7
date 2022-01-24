<?php
/**
 * Table Definition for transferencia_estado
 */
require_once 'DB/DataObject.php';

class DataObjects_Transferencia_estado extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'transferencia_estado';    // table name
    public $te_id;                           // int(11)  not_null primary_key auto_increment group_by
    public $te_desc;                         // varchar(256)  not_null
    public $te_baja;                         // int(1)  not_null group_by
    public $te_color;                        // varchar(100)  
    public $te_icono;                        // varchar(100)  

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Transferencia_estado',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
