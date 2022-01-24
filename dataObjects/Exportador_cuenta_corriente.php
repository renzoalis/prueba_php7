<?php
/**
 * Table Definition for exportador_cuenta_corriente
 */
require_once 'DB/DataObject.php';

class DataObjects_Exportador_cuenta_corriente extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'exportador_cuenta_corriente';    // table name
    public $ccte_id;                         // int(11)  not_null primary_key auto_increment group_by
    public $ccte_exportador_id;              // int(11)  not_null group_by
    public $ccte_fh;                         // datetime(19)  not_null
    public $ccte_operacion_tipo;             // int(11)  not_null group_by
    public $ccte_operacion_id;               // int(11)  not_null group_by
    public $ccte_importe;                    // float(11)  not_null group_by
    public $ccte_saldo_actual;               // float(11)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Exportador_cuenta_corriente',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
        function cargarflete($objeto,$id_flete) {
        //print_r($objeto);exit;
        $cc_ant = DB_DataObject::factory('exportador_cuenta_corriente');
        $cc_ant -> ccte_exportador_id = $objeto['input_id_exportad'];
        $cc_ant -> orderBy('ccte_id DESC');
        $cc_ant -> find(true);

        $saldo_anterior = $cc_ant -> ccte_saldo_actual;

        $monto = $objeto['input_monto'];
        
        $this -> ccte_exportador_id = $objeto['input_id_exportad']; 
        $this -> ccte_fh = date("Y-m-d H:i:s");
        $this -> ccte_operacion_tipo = 10;                                  // DATO EN TABLA CUENTA_CORRIENTE_OP -> Costo flete
        $this -> ccte_operacion_id = $id_flete;
        $this -> ccte_importe = $monto;
        $this -> ccte_saldo_actual = $saldo_anterior - $monto;
        $id = $this -> insert();
        return $id;
    }

    function cargarPago($objeto,$id_pago) {
        $cc_ant = DB_DataObject::factory('exportador_cuenta_corriente');
        $cc_ant -> ccte_exportador_id = $objeto['input_id_exportad'];
        $cc_ant -> orderBy('ccte_id DESC');
        $cc_ant -> find(true);

        $saldo_anterior = $cc_ant -> ccte_saldo_actual;

        $monto = $objeto['input_monto_total'];
        
        $this -> ccte_exportador_id = $objeto['input_id_exportad']; 
        $this -> ccte_fh = date("Y-m-d H:i:s");
        $this -> ccte_operacion_tipo = 9;
        $this -> ccte_operacion_id = $id_pago;
        $this -> ccte_importe = $monto;
        $this -> ccte_saldo_actual = $saldo_anterior + $monto;
        $id = $this -> insert();
        return $id;
    }

    function cargarNota($objeto,$id_nota) {
        $cc_ant = DB_DataObject::factory('exportador_cuenta_corriente');
        $cc_ant -> ccte_exportador_id = $objeto['input_id_exportador'];
        $cc_ant -> orderBy('ccte_id DESC');
        $cc_ant -> find(true);

        $saldo_anterior = $cc_ant -> ccte_saldo_actual;

        if($objeto['combo_tipo'] == 'NC'){
            $monto = 0 - $objeto['input_monto'];
            $tipo = 5;
        } elseif ($objeto['combo_tipo'] == 'ND') {
            $monto = $objeto['input_monto'];
            $tipo = 6;
        }

        $this -> ccte_exportador_id = $objeto['input_id_exportador']; 
        $this -> ccte_fh = date("Y-m-d H:i:s");
        $this -> ccte_operacion_tipo = $tipo;
        $this -> ccte_operacion_id = $id_nota;
        $this -> ccte_importe = $objeto['input_monto'];
        $this -> ccte_saldo_actual = $saldo_anterior + $monto;
        $id = $this -> insert();
        return $id;
    }

   function pagoExport($objeto) {
        $cc_ant = DB_DataObject::factory('exportador_cuenta_corriente');
        $cc_ant -> ccte_exportador_id = $objeto['input_id_export'];
        $cc_ant -> orderBy('ccte_id DESC');
        $cc_ant -> find(true);

        $saldo_anterior = $cc_ant -> ccte_saldo_actual;

        $monto = $objeto['input_monto_total'];
        
        $this -> ccte_exportador_id = $objeto['input_id_export']; 
        $this -> ccte_fh = date("Y-m-d H:i:s");
        $this -> ccte_operacion_tipo = 9; // DATO EN TABLA CUENTA_CORRIENTE_OP -> Entrega export
        $this -> ccte_importe = $monto;
        $this -> ccte_operacion_id = $id;
        $this -> ccte_saldo_actual = $saldo_anterior + $monto;
        $id = $this -> insert();
        return $id;
    }

    function exportadorGetCC($id, $desde, $hasta){
        $hasta = str_replace('/', '-', $hasta);
        $desde = str_replace('/', '-', $desde);

        $f_hasta = new DateTime($hasta);  
        $h = date_format($f_hasta,'Y-m-d 23:59:59');

        $f_desde = new DateTime($desde);  
        $d = date_format($f_desde,'Y-m-d 00:00:00');

        $ops = DB_DataObject::factory('cuenta_corriente_operacion');

        $this -> ccte_exportador_id = $id;
        $this -> whereAdd('ccte_fh BETWEEN "'.$d.'" AND "'.$h.'"');
        
        $this -> orderBy('ccte_id DESC');
        $this -> joinAdd($ops);
        $this -> find();

        return $this;
    }

    function getUltimaCC($id) {

        $respuesta = array();

        $this -> ccte_exportador_id = $id;
        $this -> orderBy('ccte_id DESC');
        $this -> find(true);
        
        return $this;
    }
}
