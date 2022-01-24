<?php
/**
 * Table Definition for salidas_de_caja
 */
require_once 'DB/DataObject.php';

class DataObjects_Salidas_de_caja extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'salidas_de_caja';     // table name
    public $categoria;                       // varchar(14)  not_null
    public $concepto;                        // varchar(341)  
    public $fecha;                           // datetime(19)  not_null
    public $nombre;                          // varchar(256)  
    public $monto;                           // float(11)  not_null group_by
    public $observacion;                     // varchar(512)  
    public $id;                              // int(11)  not_null group_by
    public $forma_pago;                      // varchar(45)  not_null

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Salidas_de_caja',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
