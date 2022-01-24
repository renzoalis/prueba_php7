<?php
/**
 * Table Definition for caja_detalle
 */
require_once 'DB/DataObject.php';

class DataObjects_Caja_detalle extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'caja_detalle';        // table name
    public $detalle_id;                      // int(11)  not_null primary_key auto_increment group_by
    public $detalle_caja_id;                 // int(11)  not_null group_by
    public $detalle_tipo_id;                 // int(11)  not_null group_by
    public $detalle_valor;                   // varchar(512)  

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Caja_detalle',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
