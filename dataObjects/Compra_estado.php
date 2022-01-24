<?php
/**
 * Table Definition for compra_estado
 */
require_once 'DB/DataObject.php';

class DataObjects_Compra_estado extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'compra_estado';       // table name
    public $cestado_id;                      // int(11)  not_null primary_key auto_increment group_by
    public $cestado_descripcion;             // varchar(128)  not_null
    public $cestado_baja;                    // int(1)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Compra_estado',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
