<?php
/**
 * Table Definition for boleto
 */
require_once 'DB/DataObject.php';

class DataObjects_Boleto extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'boleto';              // table name
    public $boleto_id;                       // int(11)  not_null primary_key auto_increment group_by
    public $boleto_cliente_id;               // int(11)  not_null group_by
    public $boleto_banco_id;                 // int(11)  not_null group_by
    public $boleto_emision_fh;               // date(10)  
    public $boleto_vencimiento_fh;           // date(10)  not_null
    public $boleto_cobro_fh;                 // date(10)  
    public $boleto_numero;                   // varchar(256)  not_null
    public $boleto_monto_pesos;              // float(10)  not_null group_by
    public $boleto_monto_reales;             // float(11)  group_by
    public $boleto_nfe;                      // varchar(256)  
    public $boleto_multa;                    // float(11)  group_by
    public $boleto_interes;                  // float(11)  group_by
    public $boleto_descuento;                // float(11)  group_by
    public $boleto_monto_total;              // float(11)  group_by
    public $boleto_estado;                   // int(1)  not_null group_by
    public $boleto_baja;                     // int(1)  not_null group_by
    public $boleto_descuento_fh;             // datetime(19)  

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Boleto',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function nuevoBoleto($objeto) {
        $this -> boleto_cliente_id = $objeto['input_id_cliente'];
        $this -> boleto_banco_id = $objeto['input_banco_boleto'];
        $this -> boleto_numero = $objeto['input_numero_boleto'];
        $this -> boleto_monto_pesos = $objeto['input_monto_pesos_boleto'];
        $this -> boleto_monto_reales = $objeto['input_monto_reales_boleto'];
        $this -> boleto_emision_fh = $objeto['input_emision_boleto'];
        $this -> boleto_vencimiento_fh = $objeto['input_venc_boleto'];
        $this -> boleto_nfe = $objeto['input_nfe_boleto'];
        $this -> boleto_monto_total = $objeto['input_monto_pesos_boleto'];
        $this -> boleto_estado = 1;
        $this -> boleto_baja = 0;

        $id = $this -> insert();
        return $id;
    }

    function getBoletos($estado = false) {
        $do_banco = DB_DataObject::factory('banco');
        $do_cliente = DB_DataObject::factory('cliente');
        $do_estado = DB_DataObject::factory('boleto_estado');

        $this -> joinAdd($do_cliente);
        $this -> joinAdd($do_banco);
        $this -> joinAdd($do_estado);

        if($estado) {
            $this -> whereAdd('boleto_estado = '.$estado);
        }

        $this -> boleto_baja = 0;
        $this -> find();
        return $this;
    }

    function getIngresosCaja($fhInicio,$fhCierre=false) {
        /* Por ahora, trae Multas + Intereses de los cobrados hoy */

        if(!$fhCierre){
            $fhCierre = date('Y-m-d H:i:s');
        }

        $this -> whereAdd('boleto_cobro_fh BETWEEN "'.$fhInicio.'" AND  "'.$fhCierre.'"');
        $this -> find();

        $suma = 0;
        $total['Multas'] = 0;
        $total['Intereses'] = 0;

        while($this -> fetch()){
                $total['Multas'] += $this -> boleto_multa;
                $total['Intereses'] += $this -> boleto_interes;
                $suma += $this ->  boleto_interes + $this ->  boleto_multa;
        }

        $total['Total'] = $suma;

        return $total;
    }
}
