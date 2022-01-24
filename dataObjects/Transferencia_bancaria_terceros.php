<?php
/**
 * Table Definition for transferencia_bancaria_terceros
 */
require_once 'DB/DataObject.php';

class DataObjects_Transferencia_bancaria_terceros extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'transferencia_bancaria_terceros';    // table name
    public $t_id;                            // int(11)  not_null primary_key auto_increment group_by
    public $t_cliente_id;                    // int(11)  group_by
    public $t_fh;                            // datetime(19)  
    public $t_monto;                         // float(11)  group_by
    public $t_banco_emisor_id;               // int(11)  group_by
    public $t_banco_receptor_id;             // int(11)  group_by
    public $t_comprobante;                   // varchar(256)  
    public $t_factura;                       // varchar(256)  

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Transferencia_bancaria_terceros',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function nuevaTransferencia($objeto){
 
        $this -> t_cliente_id = $objeto['input_id_cliente'];
        $this -> t_fh = date('Y-m-d H:i:s');
        $this -> t_monto = $objeto['input_monto_transfer'];
        $this -> t_banco_emisor_id = $objeto['input_banco_emisor_t'];
        $this -> t_banco_receptor_id = $objeto['input_banco_receptor_t'];
        $this -> t_comprobante = $objeto['input_comprob_transfer'];
        $this -> t_factura = $objeto['input_factura_transfer'];

        $id = $this -> insert();
        return  $id;
    }
}
