<?php
/**
 * Table Definition for cardesc_cuenta_corriente
 */
require_once 'DB/DataObject.php';

class DataObjects_Cardesc_cuenta_corriente extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'cardesc_cuenta_corriente';    // table name
    public $ccte_id;                         // int(11)  not_null primary_key auto_increment group_by
    public $ccte_cardesc_id;                 // int(11)  not_null group_by
    public $ccte_fh;                         // datetime(19)  not_null
    public $ccte_operacion_tipo;             // int(11)  not_null group_by
    public $ccte_operacion_id;               // int(11)  not_null group_by
    public $ccte_importe;                    // float(11)  not_null group_by
    public $ccte_saldo_actual;               // float(11)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Cardesc_cuenta_corriente',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    function cargarPago($objeto,$id_pago) {
        $cc_ant = DB_DataObject::factory('cardesc_cuenta_corriente');
        $cc_ant -> ccte_cardesc_id = $objeto['input_id_importad'];
        $cc_ant -> orderBy('ccte_id DESC');
        $cc_ant -> find(true);

        $saldo_anterior = $cc_ant -> ccte_saldo_actual;

        $monto = $objeto['input_monto_total'];
        
        $this -> ccte_cardesc_id = $objeto['input_id_importad']; 
        $this -> ccte_fh = date("Y-m-d H:i:s");
        $this -> ccte_operacion_tipo = 9;
        $this -> ccte_operacion_id = $id_pago;
        $this -> ccte_importe = $monto;
        $this -> ccte_saldo_actual = $saldo_anterior + $monto;
        $id = $this -> insert();
        return $id;
    }

    function cargarNota($objeto,$id_nota) {
        $cc_ant = DB_DataObject::factory('cardesc_cuenta_corriente');
        $cc_ant -> ccte_cardesc_id = $objeto['input_id_importad'];
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

        $this -> ccte_cardesc_id = $objeto['input_id_importad']; 
        $this -> ccte_fh = date("Y-m-d H:i:s");
        $this -> ccte_operacion_tipo = $tipo;
        $this -> ccte_operacion_id = $id_nota;
        $this -> ccte_importe = $objeto['input_monto'];
        $this -> ccte_saldo_actual = $saldo_anterior + $monto;
        $id = $this -> insert();
        return $id;
    }

   function pagoCarDesc($objeto) {
        $cc_ant = DB_DataObject::factory('cardesc_cuenta_corriente');
        $cc_ant -> ccte_cardesc_id = $objeto['input_id_importad'];
        $cc_ant -> orderBy('ccte_id DESC');
        $cc_ant -> find(true);

        $saldo_anterior = $cc_ant -> ccte_saldo_actual;

        $monto = $objeto['input_monto_total'];
        
        $this -> ccte_cardesc_id = $objeto['input_id_importad']; 
        $this -> ccte_fh = date("Y-m-d H:i:s");
        $this -> ccte_operacion_tipo = 9; // DATO EN TABLA CUENTA_CORRIENTE_OP -> Entrega carga/descarga
        $this -> ccte_importe = $monto;
        $this -> ccte_operacion_id = $id;
        $this -> ccte_saldo_actual = $saldo_anterior + $monto;
        $id = $this -> insert();
        return $id;
    }

        function cardescGetCC($id, $desde, $hasta){
        $hasta = str_replace('/', '-', $hasta);
        $desde = str_replace('/', '-', $desde);

        $f_hasta = new DateTime($hasta);  
        $h = date_format($f_hasta,'Y-m-d 23:59:59');

        $f_desde = new DateTime($desde);  
        $d = date_format($f_desde,'Y-m-d 00:00:00');

        $ops = DB_DataObject::factory('cuenta_corriente_operacion');

        $this -> ccte_cardesc_id = $id;
        $this -> whereAdd('ccte_fh BETWEEN "'.$d.'" AND "'.$h.'"');
        
        $this -> orderBy('ccte_id DESC');
        $this -> joinAdd($ops);
        $this -> find();

        return $this;
    }

    function getUltimaCC($id) {

        $respuesta = array();

        $this -> ccte_cardesc_id = $id;
        $this -> orderBy('ccte_id DESC');
        $this -> find(true);
        
        return $this;
    }


    function getMontosResumen($inicio,$final=false) {

        if(!$final){
            $final = date('Y-m-d H:i:s');
        }
        $respuesta['CARGA']['SALDO']['INICIO'] = 0;
        $respuesta['DESCARGA']['SALDO']['INICIO'] = 0;
        $respuesta['CARGA']['SALDO']['FINAL'] = 0;
        $respuesta['DESCARGA']['SALDO']['FINAL'] = 0;


        $do_cardesc_inicio = DB_DataObject::factory('cardesc_cuenta_corriente');
        $do_cardesc_inicio -> whereAdd('ccte_fh < "'.$inicio.'" AND ccte_cardesc_id = 1'); //CARGA AL INICIO
        $do_cardesc_inicio -> orderBy('ccte_id DESC');
        $do_cardesc_inicio -> find(true);

        if($do_cardesc_inicio -> ccte_saldo_actual){
            $respuesta['CARGA']['SALDO']['INICIO'] = $do_cardesc_inicio -> ccte_saldo_actual;
        }
        // print_r($do_cardesc_inicio);exit;

        $do_cardesc_inicio = DB_DataObject::factory('cardesc_cuenta_corriente');
        $do_cardesc_inicio -> whereAdd('ccte_fh < "'.$inicio.'" AND ccte_cardesc_id = 2'); //DESCARGA AL INICIO
        $do_cardesc_inicio -> orderBy('ccte_id DESC');
        $do_cardesc_inicio -> find(true);

        if($do_cardesc_inicio -> ccte_saldo_actual){
            $respuesta['DESCARGA']['SALDO']['INICIO'] = $do_cardesc_inicio -> ccte_saldo_actual;
        }

        $do_cardesc_inicio = DB_DataObject::factory('cardesc_cuenta_corriente');
        $do_cardesc_inicio -> whereAdd('ccte_cardesc_id = 1'); //CARGA AL FINAL
        $do_cardesc_inicio -> orderBy('ccte_id DESC');
        $do_cardesc_inicio -> find(true);

        if($do_cardesc_inicio -> ccte_saldo_actual){
            $respuesta['CARGA']['SALDO']['FINAL'] = $do_cardesc_inicio -> ccte_saldo_actual;
        }

        $do_cardesc_inicio = DB_DataObject::factory('cardesc_cuenta_corriente');
        $do_cardesc_inicio -> whereAdd('ccte_cardesc_id = 2'); //DESCARGA AL FINAL
        $do_cardesc_inicio -> orderBy('ccte_id DESC');
        $do_cardesc_inicio -> find(true);

        if($do_cardesc_inicio -> ccte_saldo_actual){
            $respuesta['DESCARGA']['SALDO']['FINAL'] = $do_cardesc_inicio -> ccte_saldo_actual;
        }
        $respuesta['CARGA']['SALDO']['DIFERENCIA'] = $respuesta['CARGA']['SALDO']['FINAL'] -  $respuesta['CARGA']['SALDO']['INICIO'];
        $respuesta['DESCARGA']['SALDO']['DIFERENCIA'] = $respuesta['DESCARGA']['SALDO']['FINAL'] -  $respuesta['DESCARGA']['SALDO']['INICIO'];

        
        return $respuesta;
    }
}

