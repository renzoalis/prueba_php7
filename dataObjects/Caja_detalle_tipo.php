<?php
/**
 * Table Definition for caja_detalle_tipo
 */
require_once 'DB/DataObject.php';

class DataObjects_Caja_detalle_tipo extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'caja_detalle_tipo';    // table name
    public $c_tipo_id;                       // int(11)  not_null primary_key auto_increment group_by
    public $c_tipo_nombre;                   // varchar(256)  not_null
    public $c_tipo_admite_numerico;          // int(1)  not_null group_by
    public $c_tipo_admite_texto;             // int(1)  not_null group_by
    public $c_tipo_baja;                     // int(1)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Caja_detalle_tipo',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
