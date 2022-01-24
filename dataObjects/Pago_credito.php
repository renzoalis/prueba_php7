<?php
/**
 * Table Definition for pago_credito
 */
require_once 'DB/DataObject.php';

class DataObjects_Pago_credito extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'pago_credito';        // table name
    public $credito_id;                      // int(11)  not_null primary_key auto_increment group_by
    public $credito_venta_id;                // int(11)  not_null group_by
    public $credito_cuota_numero;            // int(11)  not_null group_by
    public $credito_cuota_monto;             // float(11)  not_null group_by
    public $credito_cuota_vencimiento_fh;    // datetime(19)  not_null
    public $credito_cuota_pago_fh;           // datetime(19)  
    public $credito_cuota_monto_pagado;      // float(11)  group_by
    public $credito_cuota_interes_pagado;    // float(11)  group_by
    public $credito_cuota_interes;           // float(11)  group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Pago_credito',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
