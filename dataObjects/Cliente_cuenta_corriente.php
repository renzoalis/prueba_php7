<?php
/**
 * Table Definition for cliente_cuenta_corriente
 */
require_once 'DB/DataObject.php';

class DataObjects_Cliente_cuenta_corriente extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'cliente_cuenta_corriente';    // table name
    public $ccte_id;                         // int(11)  not_null primary_key auto_increment group_by
    public $ccte_cliente_id;                 // int(11)  not_null group_by
    public $ccte_fh;                         // datetime(19)  not_null
    public $ccte_operacion_tipo;             // int(11)  not_null group_by
    public $ccte_operacion_id;               // int(11)  not_null group_by
    public $ccte_importe;                    // float(11)  not_null group_by
    public $ccte_saldo_actual;               // float(11)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Cliente_cuenta_corriente',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function cargarCobro($objeto,$id_cobro) {
        $cc_ant = DB_DataObject::factory('cliente_cuenta_corriente');
        $cc_ant -> ccte_cliente_id = $objeto['input_id_cliente'];
        $cc_ant -> orderBy('ccte_id DESC');
        $cc_ant -> find(true);

        $saldo_anterior = $cc_ant -> ccte_saldo_actual;

        if($objeto['combo_fpago'] == 1){
            $monto = $objeto['input_monto_contado'];
        } 
        if ($objeto['combo_fpago'] == 6) {
            $monto = $objeto['input_monto_cheque'];
        }
        if ($objeto['combo_fpago'] == 3) { // Credito
            $monto = $objeto['input_monto_credito'];
        }
        if ($objeto['combo_fpago'] == 4) { // Debito
            $monto = $objeto['input_monto_debito'];
        }
        if ($objeto['combo_fpago'] == 5) { //  bono
            $monto = $objeto['input_monto_pesos_boleto'];
        }
        if ($objeto['combo_fpago'] == 8) { //  Transfer
            $monto = $objeto['input_monto_transfer'];
        }
        if ($objeto['combo_fpago'] == 9) { //  Deposito
            $monto = $objeto['input_monto_deposito'];
        }
        if ($objeto['combo_fpago'] == 10) { //  Cuenta Corriente
            $monto = 0;
        }

        $this -> ccte_cliente_id = $objeto['input_id_cliente']; 
        $this -> ccte_fh = date("Y-m-d H:i:s");
        $this -> ccte_operacion_tipo = 3;
        $this -> ccte_operacion_id = $id_cobro;
        $this -> ccte_importe = $monto;
        $this -> ccte_saldo_actual = $saldo_anterior + $monto;
        $id = $this -> insert();
        return $id;
    }

    function cargarNota($objeto,$id_nota) {
        $cc_ant = DB_DataObject::factory('cliente_cuenta_corriente');
        $cc_ant -> ccte_cliente_id = $objeto['input_id_cliente'];
        $cc_ant -> orderBy('ccte_id DESC');
        $cc_ant -> find(true);

        $saldo_anterior = $cc_ant -> ccte_saldo_actual;

        if($objeto['combo_tipo'] == 'NC'){
            $monto = $objeto['input_monto'];
            $tipo = 5;
        } elseif ($objeto['combo_tipo'] == 'ND') {
            $monto = 0 - $objeto['input_monto'];
            $tipo = 6;
        }

        $this -> ccte_cliente_id = $objeto['input_id_cliente']; 
        $this -> ccte_fh = date("Y-m-d H:i:s");
        $this -> ccte_operacion_tipo = $tipo;
        $this -> ccte_operacion_id = $id_nota;
        $this -> ccte_importe = $objeto['input_monto'];
        $this -> ccte_saldo_actual = $saldo_anterior + $monto;
        $id = $this -> insert();
        return $id;
    }

    function cargarVenta($objeto,$id_venta,$total_venta) {
        $cc_ant = DB_DataObject::factory('cliente_cuenta_corriente');
        $cc_ant -> ccte_cliente_id = $objeto['cliente'];
        $cc_ant -> orderBy('ccte_id DESC');
        $cc_ant -> find(true);

        $saldo_anterior = $cc_ant -> ccte_saldo_actual;

        $this -> ccte_cliente_id = $objeto['cliente']; 
        $this -> ccte_fh = date("Y-m-d H:i:s");
        $this -> ccte_operacion_tipo = 1;
        $this -> ccte_operacion_id = $id_venta;
        $this -> ccte_importe = $total_venta;
        $this -> ccte_saldo_actual = $saldo_anterior - $total_venta;
        $id = $this -> insert();
        return $id;
    }

     function anularVenta($id_cliente,$id_venta,$total_venta) {
        $cc_ant = DB_DataObject::factory('cliente_cuenta_corriente');
        $cc_ant -> ccte_cliente_id = $id_cliente;
        $cc_ant -> orderBy('ccte_id DESC');
        $cc_ant -> find(true);

        $saldo_anterior = $cc_ant -> ccte_saldo_actual;

        $this -> ccte_cliente_id = $id_cliente; 
        $this -> ccte_fh = date("Y-m-d H:i:s");
        $this -> ccte_operacion_tipo = 8;       //anulacion venta
        $this -> ccte_operacion_id = $id_venta;
        $this -> ccte_importe = $total_venta;
        $this -> ccte_saldo_actual = $saldo_anterior + $total_venta;
        $id = $this -> insert();
        return $id;
    }

    function cargarFacturacion($id_cliente,$id_venta,$monto) {
        $cc_ant = DB_DataObject::factory('cliente_cuenta_corriente');
        $cc_ant -> ccte_cliente_id = $id_cliente;
        $cc_ant -> orderBy('ccte_id DESC');
        $cc_ant -> find(true);

        $saldo_anterior = $cc_ant -> ccte_saldo_actual;

        $this -> ccte_cliente_id = $id_cliente; 
        $this -> ccte_fh = date("Y-m-d H:i:s");
        $this -> ccte_operacion_tipo = 7;       //anulacion venta
        $this -> ccte_operacion_id = $id_venta;
        $this -> ccte_importe = $monto;
        $this -> ccte_saldo_actual = $saldo_anterior - $monto;
        $id = $this -> insert();
        return $id;
    }

    function getCC($fecha_desde,$fecha_hasta) {
// DB_DataObject::debugLevel(1);
        $respuesta = array();
        /* CLIENTES */
        $do_cliente = DB_DataObject::factory('cliente');
        $this -> joinAdd($do_cliente);
        $this -> whereAdd('ccte_fh BETWEEN "'.$fecha_desde.'" AND "'.$fecha_hasta.'"');
        $this -> orderBy('ccte_id DESC');
        $this -> find();

        while($this -> fetch()) {
            $pago = '';
            $importe = '';
            $medias = '';
            $peso = '';
            $precio_u = '';
            switch ($this -> ccte_operacion_tipo) {
                case 1:
                    $operacion = 'Venta';
                    $venta = DB_DataObject::factory('venta');
                    $venta -> venta_id = $this -> ccte_operacion_id;
                    $venta -> find(true); 
                    $medias = $venta -> getDetalleFichaCliente();
                    $peso = $venta -> getPesoVenta();
                    $precio_u = $venta -> getPrecioUnitario();
                    $importe = $this -> ccte_importe;
                    break;
                case 3:
                    $operacion = 'Cobro';
                    $pago = $this -> ccte_importe;
                    break;
                case 5:
                    $operacion = 'Nota credito';
                    $pago = $this -> ccte_importe;
                    break;
                case 6:
                    $operacion = 'Nota debito';
                    $importe = $this -> ccte_importe;
                    break;
            }

            $respuesta[$this -> ccte_cliente_id]['cliente'] = $this -> cliente_nombre;
            $respuesta[$this -> ccte_cliente_id]['cliente_id'] = $this -> cliente_id;
            $respuesta[$this -> ccte_cliente_id]['cc'][$this -> ccte_id]['fecha'] = date('d-m-Y',strtotime($this -> ccte_fh));
            $respuesta[$this -> ccte_cliente_id]['cc'][$this -> ccte_id]['concepto'] = $operacion.' #'.$this -> ccte_operacion_id;
            $respuesta[$this -> ccte_cliente_id]['cc'][$this -> ccte_id]['medias'] = $medias;
            $respuesta[$this -> ccte_cliente_id]['cc'][$this -> ccte_id]['tot_kg'] = $peso;
            $respuesta[$this -> ccte_cliente_id]['cc'][$this -> ccte_id]['p_unit_kg'] = $precio_u;
            $respuesta[$this -> ccte_cliente_id]['cc'][$this -> ccte_id]['importe'] = $importe;
            $respuesta[$this -> ccte_cliente_id]['cc'][$this -> ccte_id]['pago'] = $pago;
            $respuesta[$this -> ccte_cliente_id]['cc'][$this -> ccte_id]['saldo'] = $this -> ccte_saldo_actual;

        }

        return $respuesta;
    }

    function getCuentasCorrientes() {

        $respuesta = array();

        // Busco la última CC de cada cliente.
        $this -> query("SELECT MAX(ccte_id) as id FROM cliente_cuenta_corriente GROUP BY (ccte_cliente_id)");

        $ids = '';
        $i = 0;

        while($this -> fetch()) {
            if($i) {
                $ids .= ', ';
            }
            $ids .= ''.$this -> id;
            $i ++;
        }

        $cliente = DB_DataObject::factory('cliente');
        $cc = DB_DataObject::factory('cliente_cuenta_corriente');
        $cc -> joinAdd($cliente);
        $cc -> whereAdd('ccte_id IN ('.$ids.')');
        $cc -> find();

        while ($cc -> fetch()) {
            $respuesta[$cc -> ccte_id]['id'] = ''.$cc -> cliente_id;
            $respuesta[$cc -> ccte_id]['cliente'] = $cc -> cliente_nombre;
            $respuesta[$cc -> ccte_id]['saldo'] = $cc -> ccte_saldo_actual;
        }

        return $respuesta;
    }

    function clientesGetCC($id, $desde, $hasta){
        $hasta = str_replace('/', '-', $hasta);
        $desde = str_replace('/', '-', $desde);

        $f_hasta = new DateTime($hasta);  
        $h = date_format($f_hasta,'Y-m-d 23:59:59');

        $f_desde = new DateTime($desde);  
        $d = date_format($f_desde,'Y-m-d 00:00:00');
        
        $ops = DB_DataObject::factory('cuenta_corriente_operacion');

        $this -> ccte_cliente_id = $id;
        $this -> whereAdd('ccte_fh BETWEEN "'.$d.'" AND "'.$h.'"');
        $this -> orderBy('ccte_id DESC');
        $this -> joinAdd($ops);
        $this -> find();

        return $this;
    }

    function getUltimaCC($id) {

        $respuesta = array();

        $this -> ccte_cliente_id = $id;
        $this -> orderBy('ccte_id DESC');
        $this -> find(true);
        
        return $this;
    }   

    function cargarDescuento($id_cliente,$id_descuento,$total_descuento) {
        $cc_ant = DB_DataObject::factory('cliente_cuenta_corriente');
        $cc_ant -> ccte_cliente_id = $id_cliente;
        $cc_ant -> orderBy('ccte_id DESC');
        $cc_ant -> find(true);

        $saldo_anterior = $cc_ant -> ccte_saldo_actual;

        $this -> ccte_cliente_id = $id_cliente; 
        $this -> ccte_fh = date("Y-m-d H:i:s");
        $this -> ccte_operacion_tipo =  12;       //Descuento
        $this -> ccte_operacion_id = $id_descuento;
        $this -> ccte_importe = $total_descuento;
        $this -> ccte_saldo_actual = $saldo_anterior + $total_descuento;
        $id = $this -> insert();
        return $id;
    }
    
}