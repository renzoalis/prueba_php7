<?php
/**
 * Table Definition for cheque
 */
require_once 'DB/DataObject.php';

class DataObjects_Cheque extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'cheque';              // table name
    public $cheque_id;                       // int(11)  not_null primary_key auto_increment group_by
    public $cheque_banco_id;                 // int(11)  not_null group_by
    public $cheque_numero;                   // varchar(256)  not_null
    public $cheque_monto;                    // float(10)  not_null group_by
    public $cheque_cobro_fh;                 // date(10)  not_null
    public $cheque_cliente_id;               // int(11)  not_null group_by
    public $cheque_estado;                   // int(2)  not_null group_by
    public $cheque_baja;                     // int(1)  not_null group_by
    public $cheque_proveedor_id;             // int(11)  group_by
    public $cheque_proveedor_fh;             // datetime(19)  
    public $cheque_transportista_id;         // int(11)  group_by
    public $cheque_transportista_fh;         // datetime(19)  
    public $cheque_emision_fh;               // date(10)  
    public $cheque_titular;                  // varchar(256)  
    public $cheque_ingreso_fh;               // datetime(19)  
    public $cheque_despachante_id;           // int(11)  group_by
    public $cheque_despachante_fh;           // datetime(19)  
    public $cheque_importador_id;            // int(11)  group_by
    public $cheque_importador_fh;            // datetime(19)  
    public $cheque_exportador_id;            // int(11)  group_by
    public $cheque_exportador_fh;            // datetime(19)  
    public $cheque_modificacion_fh;          // datetime(19)  
    public $cheque_cardesc_id;               // int(11)  group_by
    public $cheque_cardesc_fh;               // datetime(19)  
    public $cheque_salida_caja_usua_id;      // int(11)  group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Cheque',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function nuevoCheque($objeto) {
        $this -> cheque_banco_id = $objeto['input_banco_cheque'];
        $this -> cheque_numero = $objeto['input_numero_cheque'];
        $this -> cheque_monto = $objeto['input_monto_cheque'];
        $this -> cheque_cobro_fh = $objeto['input_cobro_cheque'];
        $this -> cheque_emision_fh = $objeto['input_emision_cheque'];
        $this -> cheque_cliente_id = $objeto['input_id_cliente'];
        $this -> cheque_estado = 1;
        $this -> cheque_baja = 0;
        $this -> cheque_titular = $objeto['input_titular_cheque'];
        $this -> cheque_ingreso_fh = date('Y-m-d H:i:s');
        $this -> cheque_modificacion_fh = date('Y-m-d H:i:s');

        $id = $this -> insert();
        return $id;
    }

    function getCheques() {
        $do_banco = DB_DataObject::factory('banco');
        $cheque_estado = DB_DataObject::factory('cheque_estado');
        $do_cliente = DB_DataObject::factory('cliente');
        $do_cobro_cliente = DB_DataObject::factory('cobro_cliente');
        $do_transportista = DB_DataObject::factory('transportista');
        $do_despachante = DB_DataObject::factory('despachante');
        $do_proveedor = DB_DataObject::factory('proveedor');
        $do_exportador = DB_DataObject::factory('exportador');
        $do_cardesc = DB_DataObject::factory('cardesc');


        $this -> joinAdd($do_cliente);
        $this -> joinAdd($do_cobro_cliente);
        $this -> joinAdd($do_transportista,'LEFT');
        $this -> joinAdd($do_proveedor,'LEFT');
        $this -> joinAdd($do_despachante,'LEFT');
        $this -> joinAdd($do_exportador,'LEFT');
        $this -> joinAdd($do_cardesc,'LEFT');
        $this -> joinAdd($do_banco);
        $this -> joinAdd($cheque_estado);
        $this -> cheque_baja = 0;
        $this -> find();

        // print_r($this);exit;
        return $this;
    }


    function actualizarCheque($objeto) {
        
        $this -> cheque_id = $objeto['id_cheque'];
        $this -> find(true);

        
        if($objeto['estado_nuevo'] == 4 ||  $objeto['estado_nuevo'] == 5 ||  $objeto['estado_nuevo'] == 6){       //Cheque sin fondos o rechazado o vencido
            if($this -> cheque_estado == 3){        //Entregado a proveedores
                $param_prov['input_id_prov']= $this -> cheque_proveedor_id;
                $param_prov['input_monto']= $this -> cheque_monto;
                $param_prov['concepto_fh']= date('Y-m-d H:i:s');
                $param_prov['input_obs_concepto'] = 'Cheque CH00'.$this -> cheque_id.' rechazado';
                $param_prov['combo_tipo'] = 5; 

                $pago_proveedor = DB_DataObject::factory('pago_proveedor');
                $pago_proveedor -> pago_cheque_tercero_id = $this -> cheque_id;
                $pago_proveedor -> find(true);

                $param_prov['pago_id']= $pago_proveedor -> pago_id;

                $notas_prov = DB_DataObject::factory('notas');
                $notas_prov -> notaProvChequeRechazado($param_prov);    //Genero nota de credito a provedor por cheque rechazado

            }
            if($this -> cheque_estado == 7){        //Entregado a transportista
                $param_transportista['input_id_transportista']= $this -> cheque_transportista_id;
                $param_transportista['input_monto']= $this -> cheque_monto;
                $param_transportista['concepto_fh']= date('Y-m-d H:i:s');
                $param_transportista['input_obs_concepto'] = 'Cheque CH00'.$this -> cheque_id.' rechazado';
                $param_transportista['combo_tipo'] = 5; 

                $pago_transportista = DB_DataObject::factory('pago_transportista');
                $pago_transportista -> pago_cheque_propio_id = $this -> cheque_id;
                $pago_transportista -> find(true);

                //print_r($pago_proveedor);exit;

                $param_transportista['pago_id']= $pago_transportista -> pago_id;

                $notas_transportista = DB_DataObject::factory('notas');
                $notas_transportista -> notaTransportistaChequeRechazado($param_transportista);    //Genero nota de credito a transportista por  cheque rechazado

            }
             if($this -> cheque_estado == 8){        //Entregado a despachante
                $param_despachante['input_id_despachante']= $this -> cheque_despachante_id;
                $param_despachante['input_monto']= $this -> cheque_monto;
                $param_despachante['concepto_fh']= date('Y-m-d H:i:s');
                $param_despachante['input_obs_concepto'] = 'Cheque CH00'.$this -> cheque_id.' rechazado';
                $param_despachante['combo_tipo'] = 5; 

                $pago_despachante = DB_DataObject::factory('pago_despachante');
                $pago_despachante -> pago_cheque_propio_id = $this -> cheque_id;
                $pago_despachante -> find(true);

                //print_r($pago_proveedor);exit;

                $param_despachante['pago_id']= $pago_despachante -> pago_id;

                $notas_despachante = DB_DataObject::factory('notas');
                $notas_despachante -> notaDespachanteChequeRechazado($param_despachante);    //Genero nota de credito a despachante por  cheque rechazado

            }
            if($this -> cheque_estado == 9){        //Entregado a importador
                $param_importador['input_id_importador']= $this -> cheque_importador_id;
                $param_importador['input_monto']= $this -> cheque_monto;
                $param_importador['concepto_fh']= date('Y-m-d H:i:s');
                $param_importador['input_obs_concepto'] = 'Cheque CH00'.$this -> cheque_id.' rechazado';
                $param_importador['combo_tipo'] = 5; 

                $pago_importador = DB_DataObject::factory('pago_importador');
                $pago_importador -> pago_cheque_propio_id = $this -> cheque_id;
                $pago_importador -> find(true);

                $param_importador['pago_id']= $pago_importador -> pago_id;

                $notas_importador = DB_DataObject::factory('notas');
                $notas_importador -> notaImportadorChequeRechazado($param_importador);    //Genero nota de credito a despachante por  cheque rechazado

            }
            if($this -> cheque_estado == 10){        //Entregado a Exportador
                $param_exportador['input_id_exportador']= $this -> cheque_exportador_id;
                $param_exportador['input_monto']= $this -> cheque_monto;
                $param_exportador['concepto_fh']= date('Y-m-d H:i:s');
                $param_exportador['input_obs_concepto'] = 'Cheque CH00'.$this -> cheque_id.' rechazado';
                $param_exportador['combo_tipo'] = 5; 

                $pago_exportador = DB_DataObject::factory('pago_exportador');
                $pago_exportador -> pago_cheque_propio_id = $this -> cheque_id;
                $pago_exportador -> find(true);

                $param_exportador['pago_id']= $pago_exportador -> pago_id;

                $notas_exportador = DB_DataObject::factory('notas');
                $notas_exportador -> notaExportadorChequeRechazado($param_exportador);    //Genero nota de credito a exportador por  cheque rechazado

            }
                //Genero nota de Debito a la CC del cliente

                $param_cli['input_id_cliente']= $this -> cheque_cliente_id;
                $param_cli['input_monto']= $this -> cheque_monto;
                $param_cli['concepto_fh']= date('Y-m-d H:i:s');
                if($objeto['estado_nuevo'] == 6){
                    $param_cli['input_obs_concepto']= 'Cheque CH00'.$this -> cheque_id.' Vencido';
                }elseif($objeto['estado_nuevo'] == 4){
                    $param_cli['input_obs_concepto']= 'Cheque CH00'.$this -> cheque_id.' Rechazado S/F';
                }elseif($objeto['estado_nuevo'] == 5){
                    $param_cli['input_obs_concepto']= 'Cheque CH00'.$this -> cheque_id.' Rechazado C/F';
                }
                $param_cli['combo_tipo'] = 'ND';

                $cobro_cliente = DB_DataObject::factory('cobro_cliente');
                $cobro_cliente -> cobro_cheque_id = $this -> cheque_id;
                $cobro_cliente -> find(true);

                $param_cli['cobro_id']= $cobro_cliente -> cobro_id;

                $notas_cli = DB_DataObject::factory('notas');
                $notas_cli -> notaClienteChequeRechazado($param_cli);   //Genero nota de debito a cliente  por cheque rechazado sin fondos o vencido
        }

        $this -> cheque_modificacion_fh = date('Y-m-d H:i:s');
        if($objeto['estado_nuevo'] == 11){ //Salida de caja por usuario
            $this -> cheque_salida_caja_usua_id = $_SESSION['usuario']['id'];
        }
        $this -> cheque_estado = $objeto['estado_nuevo'];
        if($this -> update()){
            return $this -> cheque_id;
        }
    }

    function getCantACobrar() {
        $this -> whereAdd('cheque_estado = 1 AND cheque_cobro_fh >= '.date('Y-m-d'));

        $this -> find();
        
        return $this -> N;
    }
    function getCantPlataACobrar() {
        $this -> whereAdd('cheque_estado = 1 AND cheque_cobro_fh >= '.date('Y-m-d'));

        $this -> find();
        $total= 0;
        while($this -> fetch()) {
           $total += $this ->cheque_monto;
        }
        
        return $total;
    }

    function getIngresosCaja($fhInicio,$fhCierre=false) {
        /* Cubiertos, entregados, rechazados, vencidos */
        if(!$fhCierre){
            $fhCierre = date('Y-m-d H:i:s');
        }

        $estados = DB_DataObject::factory('cheque_estado');

        $this -> joinAdd($estados);
        $this -> whereAdd('cheque_modificacion_fh BETWEEN "'.$fhInicio.'" AND  "'.$fhCierre.'" AND cheque_estado != 1 AND cheque_baja = 0');
        $this -> find();

        $suma = 0;
        $total['Cubiertos'] = 0;
        $total['Rechazados'] = 0;
        $total['Vencidos'] = 0;
        $total['Entregados'] = 0;
        $total['Salidas_de_caja'] = 0;


        while($this -> fetch()){
            if($this -> cheque_estado == 2) { // Cubiertos
                $total['Cubiertos'] += $this -> cheque_monto;
            }
            if($this -> cheque_estado == 4 || $this -> cheque_estado == 5) { // Rechazados
                $total['Rechazados'] += $this -> cheque_monto;
            }
            if($this -> cheque_estado == 6) { // Vencidos
                $total['Vencidos'] += $this -> cheque_monto;
            }
            if($this -> cheque_estado == 3 || $this -> cheque_estado == 7 || $this -> cheque_estado == 8 || $this -> cheque_estado == 9 || $this -> cheque_estado == 10) { // Entregados
                $total['Entregados'] += $this -> cheque_monto;
            }

            if($this -> cheque_estado == 11) { // Marcado como salida de caja
                $total['Salidas_de_caja'] += $this -> cheque_monto;
            }
            $total[$this -> vestado_descripcion] += $this -> cheque_monto;
            $suma += $this -> cheque_monto;
        }
        // print_r($total);exit;
        $total['Total'] = $suma;

        return $total;
    }

    function getMontoTotal() {
    $this -> whereAdd('cheque_estado = 1');

    $this -> find();
    $total= 0;
    while($this -> fetch()) {
       $total += $this ->cheque_monto;
    }
    
    return $total;
    }

    function getPagoId(){
        $do_cheques_pago = DB_DataObject::factory('cheque');
        $do_cheques_pago -> whereAdd('cheque_id = '.$this -> cheque_id);
        switch($this -> cheque_estado){

                  case '3': $do_pago_proveedor = DB_DataObject::factory('pago_proveedor');
                            $do_cheques_pago -> joinAdd($do_pago_proveedor,'LEFT');
                            $do_cheques_pago -> find(true);
                            echo "PP".$do_cheques_pago -> pago_id;
                            break;

                  case '7': $do_pago_transportista = DB_DataObject::factory('pago_transportista');
                            $do_cheques_pago -> joinAdd($do_pago_transportista,'LEFT');
                            $do_cheques_pago -> find(true);
                            echo "PT".$do_cheques_pago -> pago_id;
                            break;
                  case '8': $do_pago_despachante = DB_DataObject::factory('pago_despachante');
                            $do_cheques_pago -> joinAdd($do_pago_despachante,'LEFT');
                            $do_cheques_pago -> find(true);
                            echo "PD".$do_cheques_pago -> pago_id;
                            break;
                  case '9': $do_pago_exportador = DB_DataObject::factory('pago_exportador');
                            $do_cheques_pago -> joinAdd($do_pago_exportador,'LEFT');
                            $do_cheques_pago -> find(true);
                            echo "PE".$do_cheques_pago -> pago_id;
                            break;
                  case '10': $do_pago_importador = DB_DataObject::factory('pago_importador');
                             $do_cheques_pago -> joinAdd($do_pago_importador,'LEFT');
                            $do_cheques_pago -> find(true);
                             echo "PI".$do_cheques_pago -> pago_id;
                             break;

                  case '12': $do_pago_cardesc = DB_DataObject::factory('pago_cardesc');
                             $do_cheques_pago -> joinAdd($do_pago_cardesc,'LEFT');
                            $do_cheques_pago -> find(true);
                             echo "PCD".$do_cheques_pago -> pago_id;
                             break;

                  default: echo "-";
                           break;
          } 


    }


}
