<?php
/**
 * Table Definition for view_reporte_compras
 */
require_once 'DB/DataObject.php';

class DataObjects_View_reporte_compras extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'view_reporte_compras';    // table name
    public $ID;                              // varchar(14)  not_null
    public $FECHA;                           // datetime(19)  not_null
    public $USUARIO;                         // varchar(100)  
    public $CATEGORIA;                       // varchar(255)  
    public $PRODUCTO;                        // varchar(128)  
    public $VARIEDAD;                        // varchar(255)  
    public $LOTE;                            // int(11)  primary_key auto_increment group_by
    public $CALIBRE;                         // varchar(256)  
    public $CANTIDAD;                        // int(11)  group_by
    public $DEV;                             // int(11)  group_by
    public $VALOR_U;                         // float(11)  group_by
    public $DESCARGA_U;                      // int(11)  group_by
    public $FLETE_U;                         // float(11)  group_by
    public $PROV_ID;                         // int(11)  primary_key auto_increment group_by
    public $PROVEEDOR;                       // varchar(256)  
    public $TRANSP_ID;                       // int(11)  primary_key auto_increment group_by
    public $TRANSP;                          // varchar(256)  
    public $OBS;                             // blob(65535)  blob
    public $ESTADO;                          // varchar(128)  

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_View_reporte_compras',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
