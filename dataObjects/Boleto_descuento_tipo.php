<?php
/**
 * Table Definition for boleto_descuento_tipo
 */
require_once 'DB/DataObject.php';

class DataObjects_Boleto_descuento_tipo extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'boleto_descuento_tipo';    // table name
    public $bol_desc_tipo_id;                // int(11)  not_null primary_key auto_increment group_by
    public $bol_desc_tipo_nombre;            // varchar(256)  
    public $bol_desc_tipo_baja;              // int(1)  group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Boleto_descuento_tipo',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
