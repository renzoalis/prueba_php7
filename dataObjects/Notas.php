<?php
/**
 * Table Definition for notas
 */
require_once 'DB/DataObject.php';

class DataObjects_Notas extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'notas';               // table name
    public $nota_id;                         // int(11)  not_null primary_key auto_increment group_by
    public $nota_cliente_id;                 // int(11)  group_by
    public $nota_prov_id;                    // int(11)  group_by
    public $nota_transportista_id;           // int(11)  group_by
    public $nota_despachante_id;             // int(11)  group_by
    public $nota_tipo;                       // varchar(2)  not_null
    public $nota_monto;                      // float(11)  not_null group_by
    public $nota_fh;                         // datetime(19)  
    public $nota_alta_fh;                    // datetime(19)  
    public $nota_observacion;                // varchar(256)  
    public $nota_ccop_tipo;                  // int(11)  group_by
    public $nota_ccop_id;                    // int(11)  group_by
    public $nota_importador_id;              // int(11)  group_by
    public $nota_exportador_id;              // int(11)  group_by
    public $nota_tipo_agente;                // int(11)  group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Notas',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function nuevaNota($objeto) {
        //Solicita 
        //$objeto['input_id_cliente']
        //$objeto['input_id_prov']
        //$objeto['combo_tipo']             NC o ND
        //$objeto['input_monto']
        //$objeto['input_obs_nota']
        // Y carga una nota a cliente o prov

        if($objeto['input_id_cliente']){
            $this -> nota_tipo_agente = $objeto['input_id_agente'];
            $this -> nota_cliente_id = $objeto['input_id_cliente'];
        }
        if($objeto['input_id_prov']){
            $this -> nota_tipo_agente = $objeto['input_id_agente'];
            $this -> nota_prov_id = $objeto['input_id_prov'];
        }
        $this -> nota_tipo = $objeto['combo_tipo'];
        $this -> nota_monto = $objeto['input_monto'];
        $this -> nota_fh = $objeto['nota_fh'];
        $this -> nota_alta_fh = date('Y-m-d H:i:s');
        $this -> nota_observacion = $objeto['input_obs_nota'];
        $id = $this -> insert();

        if($objeto['input_id_cliente']){
            $cc = DB_DataObject::factory('cliente_cuenta_corriente');
            $id_cc = $cc -> cargarNota($objeto, $id);
        }

        if($objeto['input_id_prov']){
            $cc = DB_DataObject::factory('proveedor_cuenta_corriente');
            $id_cc = $cc -> cargarNota($objeto, $id);   
        }
        
        return $objeto['combo_tipo'].'00'.$id;
    }

    function getAgente(){
        switch ($this -> nota_tipo_agente) {
            case '1':
                return $this -> cliente_nombre;
                break;

            case '2':
                return $this -> prov_nombre;
                break;

            case '3':
                return $this -> despachante_nombre;
                break;
        
            case '4':
                return $this -> importador_nombre;
                break;
        
            case '5':
                return $this -> exportador_nombre;
                break;

            case '6':
                return $this -> transportista_nombre;
                break;
            
            default:
                # code...
                break;
        }


    }
    function nuevaNotaAdmin($objeto) {

        // 23/3/20. Nuevo metodo para cargar las notas desde el admin.  
        // 'input_id_agente' es el tipo de agente.
        // 'input_id_select_agente' es el id del agente en cuestion.

        //Solicita 
        //$objeto['input_id_cliente']
        //$objeto['input_id_prov']
        //$objeto['combo_tipo']             NC o ND
        //$objeto['input_monto']
        //$objeto['input_obs_nota']
        // Y carga una nota a cliente o prov

        $this -> nota_tipo = $objeto['combo_tipo'];
        $this -> nota_monto = $objeto['input_monto'];
        $this -> nota_fh = $objeto['nota_fh'];
        $this -> nota_alta_fh = date('Y-m-d H:i:s');
        $this -> nota_observacion = $objeto['input_obs_nota'];
        $this -> nota_tipo_agente = $objeto['input_id_agente'];

        if($objeto['input_id_agente'] == 1){
            $this -> nota_cliente_id = $objeto['input_id_select_agente'];
            $id = $this -> insert();
            $objeto['input_id_cliente'] = $objeto['input_id_select_agente'];
            $cc = DB_DataObject::factory('cliente_cuenta_corriente');
            $id_cc = $cc -> cargarNota($objeto, $id);   
        }

        if($objeto['input_id_agente'] == 2){
            $this -> nota_prov_id = $objeto['input_id_select_agente'];
            $id = $this -> insert();
            $cc = DB_DataObject::factory('proveedor_cuenta_corriente');
            $objeto['input_id_prov'] = $objeto['input_id_select_agente'];
            $id_cc = $cc -> cargarNota($objeto, $id);   
        }

        if($objeto['input_id_agente'] == 3){
            $this -> nota_despachante_id = $objeto['input_id_select_agente'];
            $id = $this -> insert();
            $cc = DB_DataObject::factory('despachante_cuenta_corriente');
            $objeto['input_id_despachante'] = $objeto['input_id_select_agente'];
            $id_cc = $cc -> cargarNota($objeto, $id);   
        }

        if($objeto['input_id_agente'] == 4){
            $this -> nota_importador_id = $objeto['input_id_select_agente'];
            $id = $this -> insert();
            $cc = DB_DataObject::factory('importador_cuenta_corriente');
            $objeto['input_id_importador'] = $objeto['input_id_select_agente'];
            $id_cc = $cc -> cargarNota($objeto, $id);   
        }

        if($objeto['input_id_agente'] == 5){
            $this -> nota_exportador_id = $objeto['input_id_select_agente'];
            $id = $this -> insert();
            $cc = DB_DataObject::factory('exportador_cuenta_corriente');
            $objeto['input_id_exportador'] = $objeto['input_id_select_agente'];
            $id_cc = $cc -> cargarNota($objeto, $id);   
        }

        if($objeto['input_id_agente'] == 6){
            $this -> nota_transportista_id = $objeto['input_id_select_agente'];
            $id = $this -> insert();
            $cc = DB_DataObject::factory('transportista_cuenta_corriente');
            $objeto['input_id_transportista'] = $objeto['input_id_select_agente'];
            $id_cc = $cc -> cargarNota($objeto, $id);   
        }
        
        return $objeto['combo_tipo'].'00'.$id;
    }

    function nuevaNotaDesdeConcepto($objeto) {
        $this -> nota_cliente_id = $objeto['input_id_cliente'];
        $this -> nota_tipo = $objeto['nota_tipo'];
        $this -> nota_monto = $objeto['input_monto'];
        $this -> nota_fh = $objeto['nota_fh'];
        $this -> nota_alta_fh = date('Y-m-d H:i:s');
        $this -> nota_observacion = $objeto['input_obs_nota'];
        
        if($objeto['id_dm']){
            $this -> nota_ccop_tipo = 11;
            $this -> nota_ccop_id = $objeto['id_dm'];
            
        }else{
            $this -> nota_ccop_tipo = $objeto['nota_ccop_tipo'];
            $this -> nota_ccop_id = $objeto['concepto_venta_id'];
        }
        $id = $this -> insert();

        $cc = DB_DataObject::factory('cliente_cuenta_corriente');
        $id_cc = $cc -> cargarNota($objeto, $id);

        return $id;
    }

    function getNotas($desde = false,$hasta = false) {

        $do_cliente = DB_DataObject::factory('cliente');
        $do_proveedor = DB_DataObject::factory('proveedor');
        $do_despachante = DB_DataObject::factory('despachante');
        $do_importador = DB_DataObject::factory('importador');
        $do_exportador = DB_DataObject::factory('exportador');
        $do_transportista = DB_DataObject::factory('transportista');
        $do_notas_tipo_agente = DB_DataObject::factory('notas_tipo_agente');

        $this -> joinAdd($do_cliente,"LEFT");
        $this -> joinAdd($do_proveedor,"LEFT");
        $this -> joinAdd($do_despachante,"LEFT");
        $this -> joinAdd($do_importador,"LEFT");
        $this -> joinAdd($do_exportador,"LEFT");
        $this -> joinAdd($do_transportista,"LEFT");
        $this -> joinAdd($do_notas_tipo_agente,"LEFT");

        if($desde && $hasta){
            $this -> whereAdd('nota_fh BETWEEN "'.$desde.'" AND "'.$hasta.'"');
        }

        $this -> orderBy('nota_id DESC');
        $this -> find();

        return $this;
    }


    function notaProvDesdeCompra($objeto) {
        $this -> nota_prov_id = $objeto['input_id_prov'];
        $this -> nota_tipo = $objeto['nota_tipo'];
        $this -> nota_monto = $objeto['input_monto'];
        $this -> nota_fh = $objeto['concepto_fh'];
        $this -> nota_alta_fh = date('Y-m-d H:i:s');
        $this -> nota_observacion = $objeto['input_obs_concepto'];
        if($objeto['id_dm']){
            $this -> nota_ccop_tipo = 11;
            $this -> nota_ccop_id = $objeto['id_dm'];
            
        }else{
            $this -> nota_ccop_tipo = 2;
            $this -> nota_ccop_id = $objeto['compra_id'];
        }

        $id = $this -> insert();

        $cc = DB_DataObject::factory('proveedor_cuenta_corriente');
        $id_cc = $cc -> cargarNota($objeto, $id);

        return $id;
    }

    function notaProvChequeRechazado($objeto) {
        $this -> nota_prov_id = $objeto['input_id_prov'];
        $this -> nota_tipo = 'NC';
        $this -> nota_monto = $objeto['input_monto'];
        $this -> nota_fh = $objeto['concepto_fh'];
        $this -> nota_alta_fh = date('Y-m-d H:i:s');
        $this -> nota_observacion = $objeto['input_obs_concepto'];
        $this -> nota_ccop_tipo = 4;        //Operacion por la cual genero una NC, en este caso por un pago 
        $this -> nota_ccop_id = $objeto['pago_id'];
        $id = $this -> insert();

        $cc = DB_DataObject::factory('proveedor_cuenta_corriente');
        $id_cc = $cc -> cargarNota($objeto, $id);

        return $id;
    }
    function notaTransportistaChequeRechazado($objeto) {
        //print_r($objeto);exit;
        $this -> nota_transportista_id = $objeto['input_id_transportista'];
        $this -> nota_tipo = 'NC';
        $this -> nota_monto = $objeto['input_monto'];
        $this -> nota_fh = $objeto['concepto_fh'];
        $this -> nota_alta_fh = date('Y-m-d H:i:s');
        $this -> nota_observacion = $objeto['input_obs_concepto'];
        $this -> nota_ccop_tipo = 4;        //Operacion por la cual genero una NC, en este caso por un pago 
        $this -> nota_ccop_id = $objeto['pago_id'];
        $id = $this -> insert();

        $cc = DB_DataObject::factory('transportista_cuenta_corriente');
        $id_cc = $cc -> cargarNota($objeto, $id);

        return $id;
    }

    function notaDespachanteChequeRechazado($objeto) {
        //print_r($objeto);exit;
        $this -> nota_despachante_id = $objeto['input_id_despachante'];
        $this -> nota_tipo = 'NC';
        $this -> nota_monto = $objeto['input_monto'];
        $this -> nota_fh = $objeto['concepto_fh'];
        $this -> nota_alta_fh = date('Y-m-d H:i:s');
        $this -> nota_observacion = $objeto['input_obs_concepto'];
        $this -> nota_ccop_tipo = 4;        //Operacion por la cual genero una NC, en este caso por un pago 
        $this -> nota_ccop_id = $objeto['pago_id'];
        $id = $this -> insert();

        $cc = DB_DataObject::factory('despachante_cuenta_corriente');
        $id_cc = $cc -> cargarNota($objeto, $id);

        return $id;
    }
    function notaImportadorChequeRechazado($objeto) {
        //print_r($objeto);exit;
        $this -> nota_importador_id = $objeto['input_id_importador'];
        $this -> nota_tipo = 'NC';
        $this -> nota_monto = $objeto['input_monto'];
        $this -> nota_fh = $objeto['concepto_fh'];
        $this -> nota_alta_fh = date('Y-m-d H:i:s');
        $this -> nota_observacion = $objeto['input_obs_concepto'];
        $this -> nota_ccop_tipo = 4;        //Operacion por la cual genero una NC, en este caso por un pago 
        $this -> nota_ccop_id = $objeto['pago_id'];
        $id = $this -> insert();

        $cc = DB_DataObject::factory('importador_cuenta_corriente');
        $id_cc = $cc -> cargarNota($objeto, $id);

        return $id;
    }
    function notaExportadorChequeRechazado($objeto) {
        //print_r($objeto);exit;
        $this -> nota_exportador_id = $objeto['input_id_exportador'];
        $this -> nota_tipo = 'NC';
        $this -> nota_monto = $objeto['input_monto'];
        $this -> nota_fh = $objeto['concepto_fh'];
        $this -> nota_alta_fh = date('Y-m-d H:i:s');
        $this -> nota_observacion = $objeto['input_obs_concepto'];
        $this -> nota_ccop_tipo = 4;        //Operacion por la cual genero una NC, en este caso por un pago 
        $this -> nota_ccop_id = $objeto['pago_id'];
        $id = $this -> insert();

        $cc = DB_DataObject::factory('exportador_cuenta_corriente');
        $id_cc = $cc -> cargarNota($objeto, $id);

        return $id;
    }

    function notaClienteChequeRechazado($objeto) {
        $this -> nota_cliente_id = $objeto['input_id_cliente'];
        $this -> nota_tipo = 'ND';
        $this -> nota_monto = $objeto['input_monto'];
        $this -> nota_fh = $objeto['concepto_fh'];
        $this -> nota_alta_fh = date('Y-m-d H:i:s');
        $this -> nota_observacion = $objeto['input_obs_concepto'];
        $this -> nota_ccop_tipo = 3;
        $this -> nota_ccop_id = $objeto['cobro_id'];
        $id = $this -> insert();

        $objeto['nota_fh'] = date('Y-m-d H:i:s');
        
        $cc = DB_DataObject::factory('cliente_cuenta_corriente');
        $id_cc = $cc -> cargarNota($objeto, $id);

        return $id;
    }

    function getIngresosCaja($fhInicio,$fhCierre=false) {
        /* Cubiertos, entregados, rechazados, vencidos */
        if(!$fhCierre){
            $fhCierre = date('Y-m-d H:i:s');
        }

        $this -> whereAdd('nota_alta_fh BETWEEN "'.$fhInicio.'" AND  "'.$fhCierre.'"');
        $this -> find();

        $total['ND']['Ingresos'] = 0;
        $total['ND']['Egresos'] = 0;
        $total['NC']['Ingresos'] = 0;
        $total['NC']['Egresos'] = 0;

        while($this -> fetch()){

            if($this -> nota_tipo == 'ND') {
                if(is_null($this -> nota_cliente_id)) {                  // <--- Si no son de clientes
                    $total['ND']['Egresos'] += $this -> nota_monto;
                } else {                                                 // <--- Si es de prov/transp/exp/imp/desp, es al reves
                    $total['ND']['Ingresos'] += $this -> nota_monto;
                }

            } 

            elseif($this -> nota_tipo == 'NC') {
                if(is_null($this -> nota_cliente_id)) {                  // <--- Si no son de clientes
                    $total['NC']['Ingresos'] += $this -> nota_monto;
                } else {                                                 // <--- Si es de prov/transp/exp/imp/desp, es al reves
                    $total['NC']['Egresos'] += $this -> nota_monto;
                }
            }
            
        }

        $total['NC']['Total'] = $total['NC']['Ingresos'] - $total['NC']['Egresos'];
        $total['ND']['Total'] = $total['ND']['Ingresos'] - $total['ND']['Egresos'];

        return $total;
    }


    function getMontoNotasClientes($desde = false,$hasta = false) {

        $do_notas = DB_DataObject::factory('notas');
        $do_notas -> whereAdd('nota_cliente_id IS NOT NULL AND nota_fh BETWEEN "'.$desde.'" AND "'.$hasta.'"');

        $do_notas -> find();

        $respuesta['ND'] = 0;
        $respuesta['NC'] = 0;
        while($do_notas -> fetch()){
            $respuesta[$do_notas -> nota_tipo] += $do_notas -> nota_monto;
        }

        return $respuesta;
    }


}
