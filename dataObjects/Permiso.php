<?php
/**
 * Table Definition for permiso
 */
require_once 'DB/DataObject.php';

class DataObjects_Permiso extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'permiso';             // table name
    public $permiso_id;                      // int(11)  not_null primary_key auto_increment group_by
    public $permiso_rol_id;                  // int(11)  not_null multiple_key group_by
    public $permiso_mod_id;                  // int(11)  not_null multiple_key group_by
    public $permiso_tipoacc_id;              // int(11)  not_null multiple_key group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Permiso',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
