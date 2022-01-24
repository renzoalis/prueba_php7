<?php
/**
 * Table Definition for cheque_propio
 */
require_once 'DB/DataObject.php';

class DataObjects_Cheque_propio extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'cheque_propio';       // table name
    public $cheque_id;                       // int(11)  not_null primary_key auto_increment group_by
    public $cheque_banco_id;                 // int(11)  not_null group_by
    public $cheque_numero;                   // varchar(256)  not_null
    public $cheque_monto;                    // float(10)  not_null group_by
    public $cheque_vencimiento_fh;           // date(10)  not_null
    public $cheque_proveedor_id;             // int(11)  not_null group_by
    public $cheque_estado;                   // int(2)  not_null group_by
    public $cheque_baja;                     // int(1)  not_null group_by
    public $cheque_emitido_fh;               // date(10)  
    public $cheque_titular;                  // varchar(256)  
    public $cheque_ingreso_fh;               // date(10)  
    public $cheque_transportista_id;         // int(11)  not_null group_by
    public $cheque_despachante_id;           // int(11)  not_null group_by
    public $cheque_importador_id;            // int(11)  not_null group_by
    public $cheque_exportador_id;            // int(11)  not_null group_by
    public $cheque_modificacion_fh;          // datetime(19)  
    public $cheque_cardesc_id;               // int(11)  group_by
    public $cheque_importador_fh;            // datetime(19)  

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Cheque_propio',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function nuevoCheque($objeto){
                    //print_r($objeto);exit;
        $this -> cheque_banco_id = $objeto['input_banco_cheque'];
        $this -> cheque_numero = $objeto['input_numero_cheque'];
        $this -> cheque_monto = $objeto['input_monto_cheque'];
        $this -> cheque_vencimiento_fh = $objeto['input_cobro_cheque'];
        $this -> cheque_proveedor_id = $objeto['input_id_proveedor'];
        $this -> cheque_emitido_fh = $objeto['input_emision_cheque'];
        $this -> cheque_titular = $objeto['input_titular_cheque'];
        $this -> cheque_estado = 3; // Entregado a proveedores
        $this -> cheque_ingreso_fh = date('Y-m-d');
        $this -> cheque_modificacion_fh = date('Y-m-d H:i:s');


        $id_cheque_propio = $this -> insert();
        
        return $id_cheque_propio;  
    }

    function getCheques() {
        $do_banco = DB_DataObject::factory('banco');
        $cheque_estado = DB_DataObject::factory('cheque_estado');
        $do_proveedor = DB_DataObject::factory('proveedor');
        $do_transportista = DB_DataObject::factory('transportista');
        $do_despachante = DB_DataObject::factory('despachante');
        $do_importador = DB_DataObject::factory('importador');
        $do_exportador = DB_DataObject::factory('exportador');
        $this -> joinAdd($do_importador,"LEFT");
        $this -> joinAdd($do_exportador,"LEFT");
        $this -> joinAdd($do_despachante,"LEFT");
        $this -> joinAdd($do_proveedor,"LEFT");
        $this -> joinAdd($do_banco);
        $this -> joinAdd($cheque_estado);
        $this -> cheque_baja = 0;
        $this -> find();
        return $this;
    }

      function actualizarCheque($objeto) {
        
        $this -> cheque_id = $objeto['id_cheque'];
        $this -> find(true);
       // print_r($this);exit;
        
        if($objeto['estado_nuevo'] == 4 ||  $objeto['estado_nuevo'] == 5 ||  $objeto['estado_nuevo'] == 6){       //Cheque rechazado S/F o rechazado C/F o Vencido
        if($this -> cheque_proveedor_id != 0) { // cheque entregado a proveedor    
                $param_prov['input_id_prov']= $this -> cheque_proveedor_id;
                $param_prov['input_monto']= $this -> cheque_monto;
                $param_prov['concepto_fh']= date('Y-m-d H:i:s');
                $param_prov['input_obs_concepto'] = 'Cheque CH00'.$this -> cheque_id.' rechazado';
                $param_prov['combo_tipo'] = 5; 

                $pago_proveedor = DB_DataObject::factory('pago_proveedor');
                $pago_proveedor -> pago_cheque_propio_id = $this -> cheque_id;
                $pago_proveedor -> find(true);

                //print_r($pago_proveedor);exit;

                $param_prov['pago_id']= $pago_proveedor -> pago_id;

                $notas_prov = DB_DataObject::factory('notas');
                $notas_prov -> notaProvChequeRechazado($param_prov);    //Genero nota de credito a provedor por cheque rechazado
            }
         if ($this -> cheque_transportista_id != 0){ //cheque entregado a transportista
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
        if ($this -> cheque_despachante_id != 0){ //cheque entregado a despachante
                $param_despachante['input_id_despachante']= $this -> cheque_despachante_id;
                $param_despachante['input_monto']= $this -> cheque_monto;
                $param_despachante['concepto_fh']= date('Y-m-d H:i:s');
                $param_despachante['input_obs_concepto'] = 'Cheque CH00'.$this -> cheque_id.' rechazado';
                $param_despachante['combo_tipo'] = 5; 

                $pago_despachante = DB_DataObject::factory('pago_despachante');
                $pago_despachante -> pago_cheque_propio_id = $this -> cheque_id;
                $pago_despachante -> find(true);

                $param_despachante['pago_id']= $pago_despachante -> pago_id;

                $notas_despachante = DB_DataObject::factory('notas');
                $notas_despachante -> notaDespachanteChequeRechazado($param_despachante);    //Genero nota de credito a despachante por  cheque rechazado
                }
        if ($this -> cheque_importador_id != 0){ //cheque entregado a importador
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
                $notas_importador -> notaImportadorChequeRechazado($param_importador);    //Genero nota de credito a importador por  cheque rechazado
                }
        if ($this -> cheque_exportador_id != 0){ //cheque entregado a exportador
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
        }

        $this -> cheque_estado = $objeto['estado_nuevo'];
        $this -> cheque_modificacion_fh = date('Y-m-d H:i:s');
        if($this -> update()){
            return $this -> cheque_id;
        }
    }

    function getCantACubrir() {
        $this -> whereAdd('cheque_estado = 7 OR cheque_estado = 3 OR cheque_estado = 8 OR cheque_estado = 9 OR cheque_estado = 10 ');

        $this -> find();
        
        return $this -> N;
    }
    function getCantPlataACubrir() {
        $this -> whereAdd('cheque_estado = 7 OR cheque_estado = 3 OR cheque_estado = 8 OR cheque_estado = 9 OR cheque_estado = 10 ');

        $this -> find();
                $total= 0;
        while($this -> fetch()) {
           $total += $this -> cheque_monto;
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


        while($this -> fetch()){
            if($this -> cheque_estado == 4 || $this -> cheque_estado == 5) { // Rechazados
                $total['Rechazados'] += $this -> cheque_monto;
            }
            if($this -> cheque_estado == 6) { // Vencidos
                $total['Vencidos'] += $this -> cheque_monto;
            }
            $total[$this -> vestado_descripcion] += $this -> cheque_monto;
            $suma += $this -> cheque_monto;
        }

        $total['Total'] = $suma;

        return $total;
    }
}
