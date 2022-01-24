<?php
/**
 * Table Definition for marca
 */
require_once 'DB/DataObject.php';

class DataObjects_Marca extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'marca';               // table name
    public $marca_id;                        // int(11)  not_null primary_key auto_increment group_by
    public $marca_nombre;                    // varchar(128)  not_null
    public $marca_baja;                      // tinyint(1)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Marca',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
