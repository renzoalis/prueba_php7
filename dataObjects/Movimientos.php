<?php
/**
 * Table Definition for movimientos
 */
require_once 'DB/DataObject.php';

class DataObjects_Movimientos extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'movimientos';         // table name
    public $mov_id;                          // int(11)  not_null primary_key auto_increment group_by
    public $mov_usua_id;                     // int(11)  not_null group_by
    public $mov_fh;                          // datetime(19)  not_null
    public $mov_operacion_tipo;              // int(11)  not_null group_by
    public $mov_operacion_id;                // int(11)  not_null group_by
    public $mov_importe;                     // float(11)  not_null group_by
    public $mov_saldo_actual;                // float(11)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Movimientos',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
