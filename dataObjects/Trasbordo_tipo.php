<?php
/**
 * Table Definition for trasbordo_tipo
 */
require_once 'DB/DataObject.php';

class DataObjects_Trasbordo_tipo extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'trasbordo_tipo';      // table name
    public $tt_id;                           // int(11)  not_null primary_key auto_increment group_by
    public $tt_nombre;                       // varchar(255)  not_null

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Trasbordo_tipo',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
