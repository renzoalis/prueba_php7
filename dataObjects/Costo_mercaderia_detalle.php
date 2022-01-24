<?php
/**
 * Table Definition for costo_mercaderia_detalle
 */
require_once 'DB/DataObject.php';

class DataObjects_Costo_mercaderia_detalle extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'costo_mercaderia_detalle';    // table name
    public $detalle_id;                      // int(11)  not_null primary_key auto_increment group_by
    public $detalle_cm_prod;                 // varchar(255)  not_null
    public $detalle_cm_id;                   // int(11)  not_null group_by
    public $detalle_cm_costo;                // int(11)  not_null group_by
    public $detalle_cm_cant;                 // int(11)  group_by
    public $detalle_ps_id;                   // int(11)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Costo_mercaderia_detalle',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
