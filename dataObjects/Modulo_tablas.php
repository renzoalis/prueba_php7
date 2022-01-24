<?php
/**
 * Table Definition for modulo_tablas
 */
require_once 'DB/DataObject.php';

class DataObjects_Modulo_tablas extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'modulo_tablas';       // table name
    public $modtab_id;                       // int(11)  not_null primary_key auto_increment group_by
    public $modtab_ddt_id;                   // int(11)  not_null multiple_key group_by
    public $modtab_mod_id;                   // int(11)  not_null multiple_key group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Modulo_tablas',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
