<?php
/**
 * Table Definition for usuario_rol
 */
require_once 'DB/DataObject.php';

class DataObjects_Usuario_rol extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'usuario_rol';         // table name
    public $usrrol_id;                       // int(11)  not_null primary_key auto_increment group_by
    public $usrrol_usua_id;                  // int(11)  not_null multiple_key group_by
    public $usrrol_rol_id;                   // int(11)  not_null multiple_key group_by
    public $usrrol_app_id;                   // int(11)  not_null multiple_key group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Usuario_rol',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
