<?php
/**
 * Table Definition for trasbordos
 */
require_once 'DB/DataObject.php';

class DataObjects_Trasbordos extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'trasbordos';          // table name
    public $trasb_id;                        // int(11)  not_null primary_key auto_increment group_by
    public $trasb_fh;                        // datetime(19)  not_null
    public $trasb_tipo;                      // int(2)  group_by
    public $trasb_origen;                    // int(11)  group_by
    public $trasb_destino;                   // int(11)  group_by
    public $trasb_prod_id;                   // int(11)  group_by
    public $trasb_cant;                      // int(11)  group_by
    public $trasb_obs;                       // varchar(256)  
    public $trasb_costo_carga;               // float(11)  group_by
    public $trasb_costo_descarga;            // float(11)  group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Trasbordos',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
