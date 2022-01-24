<?php
/**
 * Table Definition for estacion
 */
require_once 'DB/DataObject.php';

class DataObjects_Estacion extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'estacion';            // table name
    public $estacion_id;                     // int(11)  not_null primary_key group_by
    public $estacion_descripcion;            // char(255)  not_null
    public $estacion_baja;                   // tinyint(2)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Estacion',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
