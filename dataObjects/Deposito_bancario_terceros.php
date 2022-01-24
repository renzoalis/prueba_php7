<?php
/**
 * Table Definition for deposito_bancario_terceros
 */
require_once 'DB/DataObject.php';

class DataObjects_Deposito_bancario_terceros extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'deposito_bancario_terceros';    // table name
    public $d_id;                            // int(11)  not_null primary_key auto_increment group_by
    public $d_cliente_id;                    // int(11)  group_by
    public $d_fh;                            // datetime(19)  
    public $d_monto;                         // float(11)  group_by
    public $d_banco_id;                      // int(11)  group_by
    public $d_comprobante;                   // varchar(256)  
    public $d_factura;                       // varchar(256)  

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Deposito_bancario_terceros',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function nuevoDeposito($objeto){
 
        $this -> d_cliente_id = $objeto['input_id_cliente'];
        $this -> d_fh = date('Y-m-d H:i:s');
        $this -> d_monto = $objeto['input_monto_deposito'];
        $this -> d_banco_id = $objeto['input_banco_d'];
        $this -> d_comprobante = $objeto['input_comprob_deposito'];
        $this -> d_factura = $objeto['input_factura_deposito'];

        $id = $this -> insert();
        return  $id;
    }
}
