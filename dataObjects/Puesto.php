<?php
/**
 * Table Definition for puesto
 */
require_once 'DB/DataObject.php';

class DataObjects_Puesto extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'puesto';              // table name
    public $puesto_id;                       // int(11)  not_null primary_key auto_increment group_by
    public $puesto_nombre;                   // varchar(256)  not_null

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Puesto',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
