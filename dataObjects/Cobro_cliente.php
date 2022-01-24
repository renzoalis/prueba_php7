<?php
/**
 * Table Definition for cobro_cliente
 */
require_once 'DB/DataObject.php';

class DataObjects_Cobro_cliente extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'cobro_cliente';       // table name
    public $cobro_id;                        // int(11)  not_null primary_key auto_increment group_by
    public $cobro_fh;                        // datetime(19)  not_null
    public $cobro_cliente_id;                // int(11)  not_null group_by
    public $cobro_monto_total;               // float(11)  not_null group_by
    public $cobro_usuario_id;                // int(11)  not_null group_by
    public $cobro_forma_pago;                // int(2)  not_null group_by
    public $cobro_cheque_id;                 // int(11)  group_by
    public $cobro_bono_id;                   // int(11)  group_by
    public $cobro_observacion;               // varchar(512)  
    public $cobro_baja_fh;                   // datetime(19)  
    public $cobro_transferencia_id;          // int(11)  group_by
    public $cobro_deposito_id;               // int(11)  group_by
    public $cobro_venta_id;                  // int(11)  group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Cobro_cliente',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function nuevoCobro($objeto) {
        // Guardo el cobro
        // Genero asiento en CC
        // Creo cheque de ser necesario

        // Si es anonimo solo acepta efectivo
        if($objeto['input_id_cliente'] == 9999) {
            $objeto['combo_fpago'] = 1;
        }

        $this -> cobro_fh = date("Y-m-d H:i:s");
        $this -> cobro_cliente_id = $objeto['input_id_cliente']; 
        $this -> cobro_forma_pago = $objeto['combo_fpago'];
        $this -> cobro_usuario_id = $_SESSION['usuario']['id'];
        $this -> cobro_observacion = $objeto['input_obs_pago'];
        $this -> cobro_venta_id = $objeto['venta_id'];

        if($objeto['combo_fpago'] == 1){
            $this -> cobro_monto_total = $objeto['input_monto_contado'];
        } 
        if ($objeto['combo_fpago'] == 6) { // Creo cheque
            $cheque = DB_DataObject::factory('cheque');
            $id_cheque = $cheque -> nuevoCheque($objeto);
            $this -> cobro_monto_total = $objeto['input_monto_cheque'];
            $this -> cobro_cheque_id = $id_cheque;
        }
        if ($objeto['combo_fpago'] == 3) { // Credito
            $this -> cobro_monto_total = $objeto['input_monto_credito'];
        }
        if ($objeto['combo_fpago'] == 4) { // Debito
            $this -> cobro_monto_total = $objeto['input_monto_debito'];
        }
        if ($objeto['combo_fpago'] == 5) { // Creo bono
            $bono = DB_DataObject::factory('boleto');
            $id_bono = $bono -> nuevoBoleto($objeto);
            $this -> cobro_monto_total = $objeto['input_monto_pesos_boleto'];
            $this -> cobro_bono_id = $id_bono;
        }
        if ($objeto['combo_fpago'] == 8) { // Creo bono
            $transferencia = DB_DataObject::factory('transferencia_bancaria_terceros');
            $id_tran = $transferencia -> nuevaTransferencia($objeto);
            $this -> cobro_monto_total = $objeto['input_monto_transfer'];
            $this -> cobro_transferencia_id = $id_tran;
        }
        if ($objeto['combo_fpago'] == 9) { // Creo bono
            $deposito = DB_DataObject::factory('deposito_bancario_terceros');
            $id_depo = $deposito -> nuevoDeposito($objeto);
            $this -> cobro_monto_total = $objeto['input_monto_deposito'];
            $this -> cobro_deposito_id = $id_depo;
        }
        if ($objeto['combo_fpago'] == 10) { // Cuenta Corriente
            $this -> cobro_monto_total = 0;
        }
        
        $id_cobro = $this -> insert();
        // if($objeto['input_id_cliente'] != 9999) {
        $cc = DB_DataObject::factory('cliente_cuenta_corriente');
        $id_cc = $cc -> cargarCobro($objeto, $id_cobro);
        // }

        return $id_cobro;
    }

    function getCobros($desde = false,$hasta = false) {
        $do_usuario = DB_DataObject::factory('usuario');
        $do_cliente = DB_DataObject::factory('cliente');
        $do_cheque = DB_DataObject::factory('cheque');
        $forma_pago = DB_DataObject::factory('forma_pago');

        $this -> joinAdd($do_usuario);
        $this -> joinAdd($do_cliente);
        $this -> joinAdd($forma_pago);
        $this -> joinAdd($do_cheque,'left');
        // print_r($desde);exit;
        if($desde && $hasta){
            $this -> whereAdd('cobro_fh BETWEEN "'.$desde.'" AND "'.$hasta.' 23:59:59"');
        }

        $this -> whereAdd('cobro_baja_fh IS NULL AND cobro_venta_id IS NULL') ;
        $this -> orderBy('cobro_id DESC');
        $this -> find();

        return $this;
    }

    function getMontoCobros($desde = false,$hasta = false) {

        $do_cobro_cliente = DB_DataObject::factory('cobro_cliente');
        $cobros = $do_cobro_cliente -> getCobros($desde,$hasta);

        $respuesta['Total'] = 0;
        while($cobros -> fetch()){
            $respuesta['Total'] += $cobros -> cobro_monto_total;
        }

        return $respuesta['Total'];
    }

    function getIngresosCaja($fhInicio,$fhCierre=false) {
        if(!$fhCierre){
            $fhCierre = date('Y-m-d H:i:s');
        }
        $forma_pago = DB_DataObject::factory('forma_pago');

        $this -> joinAdd($forma_pago);
        $this -> whereAdd('cobro_fh BETWEEN "'.$fhInicio.'" AND  "'.$fhCierre.'"');

        $this -> find();
        $suma = 0;

        while($this -> fetch()){
            $total[$this -> fp_desc] += 0;
            $total[$this -> fp_desc] += $this -> cobro_monto_total;
            $suma += $this ->  cobro_monto_total;
        }
        $total['Total'] = $suma;

        return $total;
    }
    function getFechaSaldada($id_venta) {
        $do_cobro = DB_DataObject::factory('cobro_cliente'); 
        $do_cobro -> cobro_venta_id = $id_venta; 
        $do_cobro -> find(true);

        return $do_cobro -> cobro_fh;
    }
}
