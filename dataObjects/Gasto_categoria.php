<?php
/**
 * Table Definition for gasto_categoria
 */
require_once 'DB/DataObject.php';

class DataObjects_Gasto_categoria extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'gasto_categoria';     // table name
    public $gc_id;                           // int(11)  not_null primary_key auto_increment group_by
    public $gc_desc;                         // varchar(255)  not_null
    public $gc_baja;                         // int(1)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Gasto_categoria',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
