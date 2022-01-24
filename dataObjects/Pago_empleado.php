<?php
/**
 * Table Definition for pago_empleado
 */
require_once 'DB/DataObject.php';

class DataObjects_Pago_empleado extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'pago_empleado';       // table name
    public $pago_id;                         // int(11)  not_null primary_key auto_increment group_by
    public $pago_fh;                         // datetime(19)  not_null
    public $pago_emplead_id;                 // int(11)  not_null group_by
    public $pago_monto_total;                // float(11)  not_null group_by
    public $pago_usuario_id;                 // int(11)  not_null group_by
    public $pago_forma_pago;                 // int(2)  not_null group_by
    public $pago_observacion;                // varchar(256)  
    public $pago_comprob_efectivo;           // varchar(256)  
    public $pago_mes_id;                     // int(2)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Pago_empleado',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    function nuevoPago($objeto) {
        
        // Guardo el pago
        // Genero asiento en CC
        // Creo cheque de ser necesario
       // print_r($objeto);exit;
        $objeto['pago_fh'] = date("Y-m-d H:i:s");       //Por si viene de entrega dinero
       

        $this -> pago_fh = date("Y-m-d H:i:s");
        $this -> pago_emplead_id = $objeto['input_id_emplead'];
        $this -> pago_forma_pago = $objeto['combo_fpago'];
        $this -> pago_usuario_id = $_SESSION['usuario']['id'];
        if($objeto['compra_id']){
            $this -> pago_compra_id = $objeto['compra_id'];
        }
        $this -> pago_observacion = $objeto['input_obs_pago'];
        $this -> pago_mes_id = $objeto['input_id_mes'];

        if($objeto['combo_fpago'] == 6){    //CHEQUE TERCEROS   
            $do_cheque = DB_DataObject::factory('cheque');
            $do_cheque -> cheque_id = $objeto['input_cheque_terceros'];
            $do_cheque -> find(true);
            $do_cheque -> cheque_empleado_fh = date('Y-m-d H:i:s');
            $do_cheque -> cheque_empleado_id = $objeto['input_id_emplead'];
            $do_cheque -> cheque_estado = 7;
            $do_cheque -> cheque_modificacion_fh = date('Y-m-d H:i:s');
            
            $do_cheque -> update();

            $id_cheque = $do_cheque -> cheque_id;
            $objeto['input_monto_total'] =  $do_cheque -> cheque_monto;

            $this -> pago_cheque_tercero_id = $id_cheque; 
        }elseif($objeto['combo_fpago'] == 2){ //cheque propio
            $do_cheque_propio = DB_DataObject::factory('cheque_propio');
            $do_cheque_propio -> cheque_banco_id = $objeto['input_banco_cheque'];
            $do_cheque_propio -> cheque_numero = $objeto['input_numero_cheque'];
            $do_cheque_propio -> cheque_monto = $objeto['input_monto_cheque'];
            $do_cheque_propio -> cheque_vencimiento_fh = $objeto['input_cobro_cheque'];
            $do_cheque_propio -> cheque_empleado_id = $objeto['input_id_emplead'];
            $do_cheque_propio -> cheque_emitido_fh = $objeto['input_emision_cheque'];
            $do_cheque_propio -> cheque_titular = $objeto['input_titular_cheque'];
            $do_cheque_propio -> cheque_ingreso_fh = date('Y-m-d');
            $do_cheque_propio -> cheque_estado = 7;

            $objeto['input_monto_total'] =  $objeto['input_monto_cheque'];

            $id_cheque_propio = $do_cheque_propio -> insert();
            $this -> pago_cheque_propio_id = $id_cheque_propio; 
           
        }elseif($objeto['combo_fpago'] == 1){ //efectivo
            $objeto['input_monto_total'] = $objeto['input_monto'] ;
            $this -> pago_comprob_efectivo = $objeto['input_comprob_contado']; 
        }elseif($objeto['combo_fpago'] == 7){
            $objeto['input_monto_total'] =  $objeto['input_monto_emplead'];
        }elseif($objeto['combo_fpago'] == 8){ // Transferencia
             $objeto['input_monto_total'] =  $objeto['input_monto_transfer'];
            $trans = DB_DataObject::factory('transferencia_bancaria');
            $id_trans = $trans -> nuevaTransferencia($objeto);
            $this -> pago_transferencia_id = $id_trans; 
        }elseif($objeto['combo_fpago'] == 9){
            $objeto['input_monto_total'] =  $objeto['input_monto_deposito'];
            $dep = DB_DataObject::factory('deposito_bancario');
            $id_dep = $dep -> nuevoDeposito($objeto);
            $this -> pago_deposito_id = $id_dep; 
        }


        $this -> pago_monto_total = $objeto['input_monto_total']; 

        $id_pago = $this -> insert();
        $cc = DB_DataObject::factory('empleado_cuenta_corriente');
        $id_cc = $cc -> cargarPago($objeto, $id_pago);

        if($id_cc) {
            return $id_pago;
        } else {
            return "ERROR CC";
        }
    }

    function getPagos($desde = false,$hasta = false) {
        $do_usuario = DB_DataObject::factory('usuario');
        $do_empleado = DB_DataObject::factory('empleado');

        $this -> joinAdd($do_usuario);
        $this -> joinAdd($do_empleado);

        if($desde && $hasta){
            $this -> whereAdd('pago_fh BETWEEN "'.$desde.'" AND "'.$hasta.' 23:59:59"');
        }

        $this -> orderBy('pago_id DESC');
        $this -> find();

        return $this;
    } 

    function getFormaPago() {

        $forma_pago = DB_DataObject::factory('forma_pago');
        $forma_pago -> fp_id = $this -> pago_forma_pago;
        $forma_pago -> find(true);

        return $forma_pago -> fp_desc;
    } 

    function getIngresosCaja($fhInicio,$fhCierre=false) {
        if(!$fhCierre){
            $fhCierre = date('Y-m-d H:i:s');
        }
        $forma_pago = DB_DataObject::factory('forma_pago');

        $this -> joinAdd($forma_pago);
        $this -> whereAdd('pago_fh BETWEEN "'.$fhInicio.'" AND  "'.$fhCierre.'"');

        $this -> find();
        $suma = 0;

        while($this -> fetch()){
            $total[$this -> fp_desc] += 0;
            $total[$this -> fp_desc] += $this -> pago_monto_total;
            $suma += $this ->  pago_monto_total;
        }
        $total['Total'] = $suma;

        return $total;
    }

    function getMes(){
        $meses[1] = "Enero";
        $meses[2] = "Febrero";
        $meses[3] = "Marzo";
        $meses[4] = "Abril";
        $meses[5] = "Mayo";
        $meses[6] = "Junio";
        $meses[7] = "Julio";
        $meses[8] = "Agosto";
        $meses[9] = "Septiembre";
        $meses[10] = "Octubre";
        $meses[11] = "Noviembre";
        $meses[12] = "Diciembre";

        return $meses[$this -> pago_mes_id];
    }
}
