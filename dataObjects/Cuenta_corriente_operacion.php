<?php
/**
 * Table Definition for cuenta_corriente_operacion
 */
require_once 'DB/DataObject.php';

class DataObjects_Cuenta_corriente_operacion extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'cuenta_corriente_operacion';    // table name
    public $ccop_id;                         // int(11)  not_null primary_key auto_increment group_by
    public $ccop_nombre;                     // varchar(128)  not_null
    public $ccop_tabla;                      // varchar(128)  not_null
    public $ccop_baja;                       // int(1)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Cuenta_corriente_operacion',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
