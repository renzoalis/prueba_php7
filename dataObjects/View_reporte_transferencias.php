<?php
/**
 * Table Definition for view_reporte_transferencias
 */
require_once 'DB/DataObject.php';

class DataObjects_View_reporte_transferencias extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'view_reporte_transferencias';    // table name
    public $ID;                              // varchar(15)  not_null
    public $MATRIZ_ID;                       // int(11)  not_null group_by
    public $LOTE_COMPUESTO;                  // varchar(256)  
    public $FECHA;                           // datetime(19)  not_null
    public $ORIGEN;                          // int(11)  group_by
    public $DESTINO;                         // int(11)  group_by
    public $NUMERO_VIAJE;                    // varchar(256)  
    public $TRANSPORTISTA;                   // varchar(256)  
    public $CATEGORIA;                       // varchar(255)  
    public $PRODUCTO;                        // varchar(128)  
    public $VARIEDAD;                        // varchar(255)  
    public $CALIBRE;                         // varchar(256)  
    public $LOTE;                            // int(11)  primary_key auto_increment group_by
    public $CANTIDAD_ORIGEN;                 // int(11)  group_by
    public $CANTIDAD_DESTINO;                // int(11)  group_by
    public $VALOR;                           // float(11)  group_by
    public $CARGA;                           // float(11)  group_by
    public $DESCARGA;                        // float(11)  group_by
    public $FLETE_ORIGEN;                    // float(11)  group_by
    public $PPV;                             // float(11)  group_by
    public $ESTADO;                          // varchar(256)  
    public $OBS;                             // varchar(256)  

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_View_reporte_transferencias',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
