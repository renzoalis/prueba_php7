<?php
/**
 * Table Definition for rol
 */
require_once 'DB/DataObject.php';

class DataObjects_Rol extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'rol';                 // table name
    public $rol_id;                          // int(11)  not_null primary_key auto_increment group_by
    public $rol_nombre;                      // varchar(45)  not_null
    public $rol_baja;                        // tinyint(4)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Rol',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
