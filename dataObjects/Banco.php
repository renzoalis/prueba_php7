<?php
/**
 * Table Definition for banco
 */
require_once 'DB/DataObject.php';

class DataObjects_Banco extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'banco';               // table name
    public $banco_id;                        // int(2)  not_null primary_key group_by
    public $banco_nombre;                    // varchar(256)  not_null
    public $banco_baja;                      // int(1)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Banco',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
