<?php
/**
 * Table Definition for estado
 */
require_once 'DB/DataObject.php';

class DataObjects_Estado extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'estado';              // table name
    public $estado_id;                       // int(2)  not_null primary_key auto_increment group_by
    public $estado_nombre;                   // varchar(45)  not_null

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Estado',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
