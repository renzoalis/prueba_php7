<?php
/**
 * Table Definition for tipo_acceso
 */
require_once 'DB/DataObject.php';

class DataObjects_Tipo_acceso extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'tipo_acceso';         // table name
    public $tipoacc_id;                      // int(11)  not_null primary_key auto_increment group_by
    public $tipoacc_nombre;                  // varchar(45)  unique_key
    public $tipoacc_baja;                    // tinyint(4)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Tipo_acceso',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
