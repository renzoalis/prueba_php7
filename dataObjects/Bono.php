<?php
/**
 * Table Definition for bono
 */
require_once 'DB/DataObject.php';

class DataObjects_Bono extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'bono';                // table name
    public $bono_id;                         // int(11)  not_null primary_key auto_increment group_by
    public $bono_banco_id;                   // int(11)  not_null group_by
    public $bono_numero;                     // int(32)  not_null group_by
    public $bono_monto;                      // float(10)  not_null group_by
    public $bono_vencimiento_fh;             // date(10)  not_null
    public $bono_cliente_id;                 // int(11)  not_null group_by
    public $bono_estado;                     // int(1)  not_null group_by
    public $bono_baja;                       // int(1)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Bono',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
