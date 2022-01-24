<?php
/**
 * Table Definition for reporteventas
 */
require_once 'DB/DataObject.php';

class DataObjects_Reporteventas extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'reporteventas';       // table name
    public $ID;                              // int(11)  not_null primary_key auto_increment group_by
    public $USUARIO;                         // varchar(100)  
    public $CATEGORIA;                       // varchar(255)  
    public $PRODUCTO;                        // varchar(128)  
    public $VARIEDAD;                        // varchar(255)  
    public $LOTE;                            // int(11)  primary_key auto_increment group_by
    public $CANTIDAD;                        // int(11)  group_by
    public $CANTIDAD_DEV;                    // int(11)  group_by
    public $VALOR;                           // float(11)  group_by
    public $DESCUENTO;                       // float(11)  group_by
    public $CLIENTE_ID;                      // int(11)  primary_key auto_increment group_by
    public $CLIENTE;                         // varchar(256)  
    public $OBS;                             // blob(65535)  blob
    public $ESTADO;                          // varchar(128)  
    public $FORMA_PAGO;                      // varchar(45)  

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Reporteventas',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
