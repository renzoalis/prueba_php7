<?php
/**
 * Table Definition for usuario_area
 */
require_once 'DB/DataObject.php';

class DataObjects_Usuario_area extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'usuario_area';        // table name
    public $usarea_id;                       // int(11)  not_null primary_key auto_increment group_by
    public $usarea_usua_id;                  // int(11)  not_null multiple_key group_by
    public $usarea_area_id;                  // int(11)  not_null multiple_key group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Usuario_area',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
