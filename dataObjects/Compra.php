<?php
/**
 * Table Definition for compra
 */
require_once 'DB/DataObject.php';

class DataObjects_Compra extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'compra';              // table name
    public $compra_id;                       // int(11)  not_null primary_key auto_increment group_by
    public $compra_fh;                       // datetime(19)  not_null
    public $compra_prov_id;                  // int(11)  not_null group_by
    public $compra_forma_pago_id;            // int(2)  not_null group_by
    public $compra_estado_id;                // int(2)  not_null group_by
    public $compra_monto_total;              // float(11)  not_null group_by
    public $compra_cant_cuotas;              // int(2)  not_null group_by
    public $compra_usuario_id;               // int(11)  not_null group_by
    public $compra_observacion;              // blob(65535)  blob
    public $compra_transp_id;                // int(11)  group_by
    public $compra_dtv_id;                   // int(11)  group_by
    public $compra_tipo_comprobante;         // varchar(256)  
    public $compra_nro_comprobante;          // varchar(256)  
    public $compra_concepto_descargas;       // float(11)  group_by
    public $compra_concepto_fletes;          // float(11)  group_by
    public $compra_concepto_impuestos;       // float(11)  group_by
    public $compra_concepto_carga;           // float(11)  group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Compra',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

   /* 
    * function nuevaCompra($objeto)
    *   1. Crea una nueva compra
    *   2. Sumar stock
    *   3. Asigna productos al detalle de la compra
    *   4. Agregar proveedor en caso de no seleccionar
    *   6. Cargo la cuenta corriente.
    */


    function nuevaCompra($objeto) {
        $compra = DB_DataObject::factory('compra');

        // si viene con dtv la guardo
        if ($objeto['input_numero_dtv']) {
             $dtv = DB_DataObject::factory('dtv');
             $id_dtv = $dtv -> cargarDtv($objeto);

             $compra -> compra_dtv_id = $id_dtv;
         }
             
        // 1. Crea una nueva compra
        $compra -> compra_fh = date("Y-m-d H:i:s");
        $compra -> compra_prov_id = $objeto['input_id_prov'];  
        $compra -> compra_transp_id = $objeto['input_id_transp'];
        // $compra -> compra_forma_pago_id = 1;
        $compra -> compra_monto_total = $objeto['saldo_final_total'];
        $compra -> compra_cant_cuotas = $objeto['input_cant_cuotas'];
        $compra -> compra_usuario_id = $_SESSION['usuario']['id'];
        $compra -> compra_estado_id = 2; // Saldado
        $compra -> compra_observacion = $objeto['input_observacion_compra'];
        $compra -> compra_tropa = $objeto['input_numero_tropa'];
        $compra -> compra_dtv = $objeto['input_dtv_compra'];
        $compra -> compra_tipo_comprobante = $objeto['combo_tipo_comprobante'];
        $compra -> compra_nro_comprobante = $objeto['input_nro_comprob_compra'];

        $id_compra = $compra -> insert();

        // 2. Suma stock
        // 3. Asigna productos al detalle de la compras
        $suma_peso = 0;
        foreach ($objeto['prod'] as $p) {
            $producto = DB_DataObject::factory('producto'); 
            $prod = $producto -> getProductos($p['id']);
            if(is_null($p['calibre']) OR $p['calibre'] == ""){
                $p['calibre'] = "S/C";
            }

            $ps_id = $prod -> sumarStock($id_compra, $p['id'], $p['precio_kg'], $p['cantidad'], $p['calibre']);
            if($ps_id) {

                $detalle = DB_DataObject::factory('compra_detalle');
                $detalle -> detalle_compra_id = $id_compra;
                $detalle -> detalle_prod_id = $p['id'];
                $detalle -> detalle_prod_calibre = $p['calibre'];
                $detalle -> detalle_prod_cant = $p['cantidad'];
                $detalle -> detalle_prod_precio_u = $p['precio_kg'];
                $detalle -> detalle_prod_tipo = $p['tipo'];
                $detalle -> detalle_ps_id = $ps_id;
                $detalle -> insert();

                $suma_peso = $suma_peso + $p['precio_kg'];
            }
        }

        $compra_e = DB_DataObject::factory('compra');
        $compra_e -> compra_id = $id_compra;
        $compra_e -> find(true);
        $compra_e -> compra_peso_total = $suma_peso;
        $compra_e -> update();

        //La compra ahora no impacta en la CC, solo figura como asiento.. Despues carga un concepto con el costo de la mercaderia
        // $cc = DB_DataObject::factory('proveedor_cuenta_corriente');
        // $id_cc = $cc -> cargarCompra($objeto, $id_compra);

        return $id_compra;
    }


    /* 
    * function getCompra($id = false)
    *   Devuelve una o todas las compras (dependiendo si tiene ID o no), con join a:
    *   Usuario que creó la compra
    *   Proveedor
    *   Estado de compra
    */
    function getCompra($id = false) {
        $do_compras = DB_DataObject::factory('compra');

        if($id){
            $do_compras -> compra_id = $id;
        }

        $do_usuario = DB_DataObject::factory('usuario');
        $do_proveedor = DB_DataObject::factory('proveedor');
        
        $do_compras -> joinAdd($do_usuario,"LEFT");
        $do_compras -> joinAdd($do_proveedor,"LEFT");


        if($id){
            $do_compras -> find(true);


        } else {
            $do_compras -> orderBy('compra_id DESC');
            $do_compras -> find();
        }
        return $do_compras;
    }

    function getCompras($desde = false,$hasta = false) {

        $do_usuario = DB_DataObject::factory('usuario');
        $do_proveedor = DB_DataObject::factory('proveedor');

        $this -> joinAdd($do_usuario);
        $this -> joinAdd($do_proveedor);

        if($desde && $hasta){
            $this -> whereAdd('compra_fh BETWEEN "'.$desde.'" AND "'.$hasta.'"');
        }

        $this -> orderBy('compra_id DESC');
        $this -> find();

        return $this;
    }

    function getDetalleString() {
        $detalle = DB_DataObject::factory('compra_detalle');
        $detalle -> detalle_compra_id = $this -> compra_id;
        $producto = DB_DataObject::factory('producto');
        $detalle -> joinAdd($producto);
        $detalle -> find();

        $respuesta = '';
        $i = 0;
        while ($detalle -> fetch()) {
            if($i == 0) {
                $respuesta .= $detalle -> detalle_prod_cant . 'x ' . $detalle -> prod_nombre;
            } else {
                $respuesta .= ', ' . $detalle -> detalle_prod_cant . 'x ' . $detalle -> prod_nombre;
            }
            $i ++;
        }

        return $respuesta;
    }

    function getComprasEstadisticos($f_desde = false,$f_hasta = false) {
        $respuesta = array();

        if(!$f_desde) {
            $this -> compra_fh > date('Y-01-01 00:00:00');
        } else {
            $this -> whereAdd('compra_fh BETWEEN "'.$f_desde.'" AND "'.$f_hasta.'"');
        }

        $usr = DB_DataObject::factory('usuario'); 
        $this -> joinAdd($usr);

        $this -> find();

        while ($this -> fetch()) {
            $respuesta['compra_'.$this -> compra_id]['fecha'] = $this -> compra_fh;
            $respuesta['compra_'.$this -> compra_id]['concepto'] = 'Compra #'.$this -> compra_id.' ('.$this -> getDetalleString().')';
            $respuesta['compra_'.$this -> compra_id]['monto'] = 0 - $this -> compra_monto_total;
            $respuesta['compra_'.$this -> compra_id]['observacion'] = $this -> compra_observacion;
            $respuesta['compra_'.$this -> compra_id]['usuario'] = $this -> usua_nombre;
            $respuesta['compra_'.$this -> compra_id]['color'] = '#f7e3e0';
        }

        return $respuesta;
    }

    function getComprasPorMes() {
        $respuesta = array();

        $respuesta['cantidad'][9] = 0;
        $respuesta['cantidad'][8] = 0;
        $respuesta['cantidad'][7] = 0;
        $respuesta['cantidad'][6] = 0;
        $respuesta['cantidad'][5] = 0;
        $respuesta['cantidad'][4] = 0;
        $respuesta['cantidad'][3] = 0;
        $respuesta['cantidad'][2] = 0;
        $respuesta['cantidad'][1] = 0;

        $fecha_mesactual = new DateTime();
        
        for ($i=9; $i > 0; $i--) { 
            $k = $i - 1;
            $fecha_mesactual -> modify("-$k month");
            $obj = clone $this;
            $mes = $fecha_mesactual -> format('m');
            $anio = $fecha_mesactual -> format('Y');
            $obj -> whereAdd('MONTH(compra_fh) = '.$mes.' AND YEAR(compra_fh) = '.$anio);
            $obj -> find();
            $respuesta['mes'][] = strftime('%b',strtotime($fecha_mesactual -> format('Y-m-d')));
            while($obj -> fetch()){
                $respuesta['cantidad'][$i] += $obj -> compra_monto_total;  
            }
            $fecha_mesactual -> modify("+$k month");
        }

        return $respuesta;
    }

    function agregarTransportista($o) {
        $this -> whereAdd('compra_id = '.$o['id_compra_transp'].'');
        $this -> find(true);

        $this -> compra_transp_id = $o['input_id_transp_e'];
        $this -> update();

        return $o['id_compra_transp'];
    }


    function actualizarObservacion($o) {
        $this -> whereAdd('compra_id = '.$o['id_compra_obs'].'');
        $this -> find(true);

        $this -> compra_observacion = $o['input_observacion_compra'];
        $this -> update();

        return $o['id_compra_obs'];
        
    }

    function actualizarMontoTotal() {
        $compra_detalle = DB_DataObject::factory('compra_detalle');
        $compra_detalle -> whereAdd('detalle_compra_id = '.$this -> compra_id);
        $compra_detalle -> find();

        $monto_total = 0;
        while($compra_detalle -> fetch()){
            $monto_total = $monto_total + ($compra_detalle -> detalle_prod_cant * $compra_detalle -> detalle_prod_precio_u);
            // print_r($compra_detalle -> detalle_prod_cant .' * '.$compra_detalle -> detalle_prod_precio_u.'<br>');
        }
        // print_r($monto_total);exit;
        // exit;
        $this -> compra_monto_total = $monto_total;
        $ok = $this -> update();

        return $ok;
        
    }

    function getCostosXLote($lote_id){

        $do_producto_stock = DB_DataObject::factory('producto_stock');
        $do_producto_stock -> ps_id = $lote_id;
        $do_producto_stock -> find(true);

        $do_compra = DB_DataObject::factory('compra');
        $do_compra_detalle = DB_DataObject::factory('compra_detalle');
        $do_compra -> joinAdd($do_compra_detalle);

        $do_compra -> whereAdd('detalle_compra_id = '.$do_producto_stock -> ps_compra_id);



    }

    function getCantidadSinCostos($fecha_desde,$fecha_hasta){
        if(!$fecha_hasta){
            $fecha_hasta = date('Y-m-d H:i:s');
        }
        $do_compra = DB_DataObject::factory('compra');
        $do_compra_detalle = DB_DataObject::factory('compra_detalle');
        $do_compra -> joinAdd($do_compra_detalle);
        //compra_concepto_fletes IS NULL OR
        $do_compra -> whereAdd('(compra_fh BETWEEN "'.$fecha_desde.'" AND "'.$fecha_hasta.'") AND ( compra_concepto_descargas IS NULL OR detalle_prod_precio_u IS NULL)');
        $do_compra -> find();

        return $do_compra -> N;
    }


    

}