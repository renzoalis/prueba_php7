<?php
/**
 * Table Definition for compra_concepto_tipo
 */
require_once 'DB/DataObject.php';

class DataObjects_Compra_concepto_tipo extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'compra_concepto_tipo';    // table name
    public $cc_tipo_id;                      // int(11)  not_null primary_key auto_increment group_by
    public $cc_tipo_nombre;                  // varchar(90)  not_null
    public $cc_tipo_baja;                    // int(1)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Compra_concepto_tipo',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
