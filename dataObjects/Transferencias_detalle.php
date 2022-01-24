<?php
/**
 * Table Definition for transferencias_detalle
 */
require_once 'DB/DataObject.php';

class DataObjects_Transferencias_detalle extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'transferencias_detalle';    // table name
    public $detalle_id;                      // int(11)  not_null primary_key auto_increment group_by
    public $detalle_transferencia_id;        // int(11)  not_null group_by
    public $detalle_producto_id;             // int(11)  not_null group_by
    public $detalle_producto_cantidad_origen;    // int(11)  not_null group_by
    public $detalle_costo_descarga;          // float(11)  group_by
    public $detalle_calibre;                 // varchar(256)  
    public $detalle_costo_carga;             // float(11)  group_by
    public $detalle_producto_cantidad_destino;    // int(11)  not_null group_by
    public $detalle_costo_unitario;          // float(11)  group_by
    public $detalle_ppv;                     // float(11)  not_null group_by
    public $detalle_ppv_fh_notif;            // datetime(19)  
    public $detalle_lote;                    // int(11)  group_by
    public $detalle_lote_desc;               // varchar(256)  
    public $detalle_costo_flete_origen;      // float(11)  group_by
    public $detalle_costo_flete_destino;     // float(11)  group_by
    public $detalle_liquidado_fh;            // datetime(19)  
    public $detalle_mercaderia_diferencia;    // int(11)  group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Transferencias_detalle',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
