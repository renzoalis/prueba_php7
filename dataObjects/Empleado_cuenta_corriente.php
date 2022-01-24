<?php
/**
 * Table Definition for empleado_cuenta_corriente
 */
require_once 'DB/DataObject.php';

class DataObjects_Empleado_cuenta_corriente extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'empleado_cuenta_corriente';    // table name
    public $ccte_id;                         // int(11)  not_null primary_key auto_increment group_by
    public $ccte_empleado_id;                // int(11)  not_null group_by
    public $ccte_fh;                         // datetime(19)  not_null
    public $ccte_operacion_tipo;             // int(11)  not_null group_by
    public $ccte_operacion_id;               // int(11)  not_null group_by
    public $ccte_importe;                    // float(11)  not_null group_by
    public $ccte_saldo_actual;               // float(11)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Empleado_cuenta_corriente',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    function cargarPago($objeto,$id_pago) {
        $cc_ant = DB_DataObject::factory('empleado_cuenta_corriente');
        $cc_ant -> ccte_empleado_id = $objeto['input_id_emplead'];
        $cc_ant -> orderBy('ccte_id DESC');
        $cc_ant -> find(true);

        $saldo_anterior = $cc_ant -> ccte_saldo_actual;

        $monto = $objeto['input_monto_total'];
        
        $this -> ccte_empleado_id = $objeto['input_id_emplead']; 
        $this -> ccte_fh = date("Y-m-d H:i:s");
        $this -> ccte_operacion_tipo = 9;
        $this -> ccte_operacion_id = $id_pago;
        $this -> ccte_importe = $monto;
        $this -> ccte_saldo_actual = $saldo_anterior + $monto;
        $id = $this -> insert();
        return $id;
    }

    function cargarNota($objeto,$id_nota) {
        $cc_ant = DB_DataObject::factory('empleado_cuenta_corriente');
        $cc_ant -> ccte_empleado_id = $objeto['input_id_empleado'];
        $cc_ant -> orderBy('ccte_id DESC');
        $cc_ant -> find(true);

        $saldo_anterior = $cc_ant -> ccte_saldo_actual;

        $this -> ccte_empleado_id = $objeto['input_id_empleado']; 
        $this -> ccte_fh = date("Y-m-d",strtotime($objeto['concepto_fh']));
        $this -> ccte_operacion_tipo = $objeto['combo_tipo'];
        $this -> ccte_operacion_id = $id_nota;
        $this -> ccte_importe = $objeto['input_monto'];
        if($objeto['combo_tipo'] == 6){     //Nota de Debito
            $this -> ccte_saldo_actual = $saldo_anterior + $objeto['input_monto'];
        }elseif($objeto['combo_tipo'] == 5){    //Nota de Credito
            $this -> ccte_saldo_actual = $saldo_anterior - $objeto['input_monto'];
        }
        $id = $this -> insert();
        return $id;
    }

   function pagoEmplead($objeto) {
        $cc_ant = DB_DataObject::factory('empleado_cuenta_corriente');
        $cc_ant -> ccte_empleado_id = $objeto['input_id_emplead'];
        $cc_ant -> orderBy('ccte_id DESC');
        $cc_ant -> find(true);

        $saldo_anterior = $cc_ant -> ccte_saldo_actual;

        $monto = $objeto['input_monto_total'];
        
        $this -> ccte_empleado_id = $objeto['input_id_emplead']; 
        $this -> ccte_fh = date("Y-m-d H:i:s");
        $this -> ccte_operacion_tipo = 9; // DATO EN TABLA CUENTA_CORRIENTE_OP -> Entrega emplead
        $this -> ccte_importe = $monto;
        $this -> ccte_operacion_id = $id;
        $this -> ccte_saldo_actual = $saldo_anterior + $monto;
        $id = $this -> insert();
        return $id;
    }


    function getMes() {
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

        $pago = DB_DataObject::factory('pago_empleado');
        $pago -> pago_id = $this -> ccte_operacion_id;
        $pago -> find(true);

        return $meses[$pago -> pago_mes_id];

    }

    function empleadoGetCC($id, $desde, $hasta){
        $hasta = str_replace('/', '-', $hasta);
        $desde = str_replace('/', '-', $desde);

        $f_hasta = new DateTime($hasta);  
        $h = date_format($f_hasta,'Y-m-d 23:59:59');

        $f_desde = new DateTime($desde);  
        $d = date_format($f_desde,'Y-m-d 00:00:00');

        $ops = DB_DataObject::factory('cuenta_corriente_operacion');

        $this -> ccte_empleado_id = $id;
        $this -> whereAdd('ccte_fh BETWEEN "'.$d.'" AND "'.$h.'"');
        
        $this -> orderBy('ccte_id DESC');
        $this -> joinAdd($ops);
        $this -> find();

        return $this;
    }

    function getUltimaCC($id) {

        $respuesta = array();

        $this -> ccte_empleado_id = $id;
        $this -> orderBy('ccte_id DESC');
        $this -> find(true);
        
        return $this;
    }

}
