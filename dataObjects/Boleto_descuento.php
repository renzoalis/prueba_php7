<?php
/**
 * Table Definition for boleto_descuento
 */
require_once 'DB/DataObject.php';

class DataObjects_Boleto_descuento extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'boleto_descuento';    // table name
    public $bol_desc_id;                     // int(11)  not_null primary_key auto_increment group_by
    public $bol_desc_bol_id;                 // int(11)  group_by
    public $bol_desc_monto;                  // float(10)  group_by
    public $bol_desc_fh;                     // datetime(19)  
    public $bol_desc_tipo;                   // int(11)  group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Boleto_descuento',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE


     function nuevaDescuento($objeto){
        //print_r($objeto);exit;
        $this -> bol_desc_bol_id = $objeto['boleto_id'];
        $this -> bol_desc_monto = $objeto['input_monto'];
        $this -> bol_desc_fh = date('Y-m-d H:i:s');
        $this -> bol_desc_tipo = 2; //Descuento parcial

        $id = $this -> insert();

        $cc_cliente = DB_DataObject::factory('cliente_cuenta_corriente');
        $cc_cliente -> cargarDescuento($objeto['input_id_cliente'],$id,$objeto['input_monto']);     //Aplico el descuento en la CC del cliente

        return $id;
     }
}
