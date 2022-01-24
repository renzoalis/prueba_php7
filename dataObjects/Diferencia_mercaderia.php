<?php
/**
 * Table Definition for diferencia_mercaderia
 */
require_once 'DB/DataObject.php';

class DataObjects_Diferencia_mercaderia extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'diferencia_mercaderia';    // table name
    public $dif_id;                          // int(11)  not_null primary_key auto_increment group_by
    public $dif_fh;                          // datetime(19)  not_null
    public $dif_usua_id;                     // int(11)  not_null group_by
    public $dif_lote;                        // int(11)  not_null group_by
    public $dif_transferencia_id;            // int(11)  not_null group_by
    public $dif_detalle_id;                  // int(11)  not_null group_by
    public $dif_cantidad;                    // int(11)  not_null group_by
    public $dif_restauro_stock;              // int(1)  not_null group_by
    public $dif_obs;                         // varchar(512)  not_null
    public $dif_prod_desc;                   // varchar(512)  not_null

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Diferencia_mercaderia',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
