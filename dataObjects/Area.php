<?php
/**
 * Table Definition for area
 */
require_once 'DB/DataObject.php';

class DataObjects_Area extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'area';                // table name
    public $area_id;                         // int(11)  not_null primary_key auto_increment group_by
    public $area_nombre;                     // varchar(45)  
    public $area_baja;                       // tinyint(4)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Area',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
