<?php
/**
 * Table Definition for pago_cardesc
 */
require_once 'DB/DataObject.php';

class DataObjects_Pago_cardesc extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'pago_cardesc';        // table name
    public $pago_id;                         // int(11)  not_null primary_key auto_increment group_by
    public $pago_fh;                         // datetime(19)  not_null
    public $pago_cardesc_id;                 // int(11)  not_null group_by
    public $pago_compra_id;                  // int(11)  group_by
    public $pago_monto_total;                // float(11)  not_null group_by
    public $pago_usuario_id;                 // int(11)  not_null group_by
    public $pago_forma_pago;                 // int(2)  not_null group_by
    public $pago_observacion;                // varchar(256)  
    public $pago_cheque_tercero_id;          // int(11)  group_by
    public $pago_transferencia_id;           // int(11)  group_by
    public $pago_cheque_propio_id;           // int(11)  group_by
    public $pago_comprob_efectivo;           // varchar(256)  
    public $pago_deposito_id;                // int(11)  group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Pago_cardesc',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    function nuevoPago($objeto) {
        // Guardo el pago
        // Genero asiento en CC
        // Creo cheque de ser necesario
        //print_r($objeto);exit;
        $this -> pago_fh = date("Y-m-d H:i:s");
        $this -> pago_cardesc_id = $objeto['input_id_importad'];
        $this -> pago_forma_pago = $objeto['combo_fpago'];
        $this -> pago_usuario_id = $_SESSION['usuario']['id'];
        if($objeto['compra_id']){
            $this -> pago_compra_id = $objeto['compra_id'];
        }
        $this -> pago_observacion = $objeto['input_obs_pago'];

        if($objeto['combo_fpago'] == 6){    //CHEQUE TERCEROS   
            $do_cheque = DB_DataObject::factory('cheque');
            $do_cheque -> cheque_id = $objeto['input_cheque_terceros'];
            $do_cheque -> find(true);
            $do_cheque -> cheque_cardesc_fh = date('Y-m-d H:i:s');
            $do_cheque -> cheque_cardesc_id = $objeto['input_id_importad'];
            $do_cheque -> cheque_estado = 12;
            $do_cheque -> cheque_modificacion_fh = date('Y-m-d H:i:s');
            
            $do_cheque -> update();

            $id_cheque = $do_cheque -> cheque_id;
            $objeto['input_monto_total'] =  $do_cheque -> cheque_monto;

            $this -> pago_cheque_tercero_id = $id_cheque; 
        }elseif($objeto['combo_fpago'] == 2){
            $objeto['input_monto_total'] =  $objeto['input_monto_cheque'];
            $do_cheque_propio = DB_DataObject::factory('cheque_propio');
            $id_cheque_propio = $do_cheque_propio -> nuevoCheque($objeto);
            $this -> pago_cheque_propio_id = $id_cheque_propio; 
        }elseif($objeto['combo_fpago'] == 1){
            $objeto['input_monto_total'] =  $objeto['input_monto'];
            $this -> pago_comprob_efectivo = $objeto['input_comprob_contado']; 
        }elseif($objeto['combo_fpago'] == 7){
            $objeto['input_monto_total'] =  $objeto['input_monto_transp'];
        }elseif($objeto['combo_fpago'] == 8){
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

        $cc = DB_DataObject::factory('cardesc_cuenta_corriente');
        $id_cc = $cc -> cargarPago($objeto, $id_pago);

        if($id_cc) {
            return $id_pago;
        } else {
            return "ERROR CC";
        }
    }

    function getPagos($desde = false,$hasta = false) {
        $do_usuario = DB_DataObject::factory('usuario');
        $do_cardesc = DB_DataObject::factory('cardesc');
        $forma_pago = DB_DataObject::factory('forma_pago');

        $this -> joinAdd($do_usuario);
        $this -> joinAdd($do_cardesc,"LEFT");
        $this -> joinAdd($forma_pago);

        if($desde && $hasta){
            $this -> whereAdd('pago_fh BETWEEN "'.$desde.'" AND "'.$hasta.' 23:59:59"');
        }

        $this -> orderBy('pago_id DESC');
        $this -> find();

        return $this;
    } 

    function getPagosArray($desde = false,$hasta = false) {

        $do_usuario = DB_DataObject::factory('usuario');
        $do_cardesc = DB_DataObject::factory('cardesc');
        $forma_pago = DB_DataObject::factory('forma_pago');

        $this -> joinAdd($do_usuario);
        $this -> joinAdd($do_cardesc,"LEFT");
        $this -> joinAdd($forma_pago);

        if($desde && $hasta){
            $this -> whereAdd('pago_fh BETWEEN "'.$desde.'" AND "'.$hasta.' 23:59:59"');
        }

        $this -> orderBy('pago_id DESC');
        $this -> find();

        return $this;
    } 

    function getFormaPago($id) {

        $forma_pago = DB_DataObject::factory('forma_pago');
        $forma_pago -> fp_id = $id;
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
        $suma_carga = 0;
        $suma_descarga = 0;

        while($this -> fetch()){    
            if($this -> pago_cardesc_id == 1){  //CARGA
                $total['Carga'][$this -> fp_desc] += 0;
                $total['Carga'][$this -> fp_desc] += $this -> pago_monto_total;
                $suma_carga += $this ->  pago_monto_total;
            }elseif($this -> pago_cardesc_id == 2){     //DESCARGA
                $total['Descarga'][$this -> fp_desc] += 0;
                $total['Descarga'][$this -> fp_desc] += $this -> pago_monto_total;
                $suma_descarga += $this ->  pago_monto_total;
            }
        }
        $total['Total']['Carga'] = $suma_carga;
        $total['Total']['Descarga'] = $suma_descarga;

        return $total;
    }
}