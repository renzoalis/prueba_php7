<?php
/**
 * Table Definition for conciliacion_tipo
 */
require_once 'DB/DataObject.php';

class DataObjects_Conciliacion_tipo extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'conciliacion_tipo';    // table name
    public $c_tipo_id;                       // int(11)  not_null primary_key auto_increment group_by
    public $c_tipo_nombre;                   // varchar(90)  not_null
    public $c_tipo_baja;                     // int(1)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Conciliacion_tipo',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function getTipos(){
        $this -> c_tipo_baja = 0;
        $this -> find();

        while ($this -> fetch()) {
            $resp[$this -> c_tipo_id] = $this -> c_tipo_nombre;
        }
        return $resp;
    }
}
