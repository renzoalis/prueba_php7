<?php
/**
 * Table Definition for view_reporte_lotes
 */
require_once 'DB/DataObject.php';

class DataObjects_View_reporte_lotes extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'view_reporte_lotes';    // table name
    public $ID;                              // int(11)  not_null primary_key auto_increment group_by
    public $ORIGEN;                          // varchar(13)  not_null
    public $PROVEEDOR;                       // varchar(256)  
    public $FECHA_COMPRA;                    // varchar(19)  
    public $FECHA_TRANSF;                    // varchar(19)  
    public $CATEGORIA;                       // varchar(255)  
    public $PRODUCTO;                        // varchar(128)  
    public $VARIEDAD;                        // varchar(255)  
    public $CALIBRE;                         // varchar(256)  
    public $CANTIDAD;                        // int(11)  not_null group_by
    public $VALOR_U;                         // float(11)  not_null group_by
    public $DESCARGA_U;                      // int(11)  group_by
    public $CARGA_U;                         // int(11)  group_by
    public $FLETE_U;                         // float(11)  group_by
    public $PPV;                             // float(11)  not_null group_by
    public $PPV_FH;                          // datetime(19)  
    public $TRANSPORTISTA;                   // varchar(256)  

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_View_reporte_lotes',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
