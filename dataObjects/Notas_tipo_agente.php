<?php
/**
 * Table Definition for notas_tipo_agente
 */
require_once 'DB/DataObject.php';

class DataObjects_Notas_tipo_agente extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'notas_tipo_agente';    // table name
    public $ta_id;                           // int(11)  not_null primary_key auto_increment group_by
    public $ta_nombre;                       // varchar(255)  not_null
    public $ta_baja;                         // tinyint(1)  not_null group_by
    public $ta_tabla;                        // varchar(255)  

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Notas_tipo_agente',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
