<?php
/**
 * Table Definition for tarjeta
 */
require_once 'DB/DataObject.php';

class DataObjects_Tarjeta extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'tarjeta';             // table name
    public $tarjeta_id;                      // int(2)  not_null primary_key group_by
    public $tarjeta_nombre;                  // varchar(256)  not_null
    public $tarjeta_baja;                    // int(1)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Tarjeta',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
