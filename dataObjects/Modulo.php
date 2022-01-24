<?php
/**
 * Table Definition for modulo
 */
require_once 'DB/DataObject.php';

class DataObjects_Modulo extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'modulo';              // table name
    public $mod_id;                          // int(11)  not_null primary_key auto_increment group_by
    public $mod_app_id;                      // int(11)  not_null multiple_key group_by
    public $mod_nombre;                      // varchar(45)  not_null
    public $mod_baja;                        // tinyint(4)  not_null group_by
    public $mod_color;                       // varchar(20)  not_null
    public $mod_intro;                       // varchar(45)  
    public $mod_icono;                       // varchar(40)  not_null
    public $mod_index_modpag_id;             // int(11)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Modulo',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
