<?php
/**
 * Table Definition for venta
 */
require_once 'DB/DataObject.php';

class DataObjects_Venta extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'venta';               // table name
    public $venta_id;                        // int(11)  not_null primary_key auto_increment group_by
    public $venta_fh;                        // datetime(19)  not_null
    public $venta_cliente_id;                // int(11)  not_null group_by
    public $venta_forma_pago_id;             // int(2)  not_null group_by
    public $venta_estado_id;                 // int(2)  not_null group_by
    public $venta_monto_total;               // float(11)  not_null group_by
    public $venta_descuento_total;           // float(11)  not_null group_by
    public $venta_cant_cuotas;               // int(2)  not_null group_by
    public $venta_usuario_id;                // int(11)  not_null group_by
    public $venta_monto_contado;             // float(11)  not_null group_by
    public $venta_observacion;               // blob(65535)  blob
    public $venta_baja_fh;                   // datetime(19)  
    public $venta_archivada_fh;              // datetime(19)  
    public $venta_despachada_fh;             // datetime(19)  
    public $venta_nro;                       // int(11)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Venta',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
     function getListadoVentas($estado = false) {
        $do_ventas = DB_DataObject::factory('venta');

        if($estado){
            $do_ventas -> venta_estado_id = $estado;
        }

        $do_usuario = DB_DataObject::factory('usuario');
        $do_ventas -> joinAdd($do_usuario);

        $do_cliente = DB_DataObject::factory('cliente');
        $do_ventas -> joinAdd($do_cliente,"LEFT");


        $do_ventas -> orderBy('venta_id DESC');
        $do_ventas -> find();
        
        return $do_ventas;
    }
    /* 
    * function nuevaVenta($objeto)
    *   1. Crea una nueva venta
    *   2. Resta stock
    *   3. Asigna productos al detalle de la venta
    *   4. En caso de crédito, se crean las cuotas y se asignan a la venta
    *   5. Agregar cliente en caso de no seleccionar
    *   6. Cargo la cuenta corriente.
    */
    function nuevaVenta($objeto) {

        //Obtengo el numero de la venta anterior
        $caja = DB_DataObject::factory('caja');
        $caja -> getUltimaCaja();

        $venta_anterior = DB_DataObject::factory('venta');
        $venta_anterior -> whereAdd('venta_fh BETWEEN "'.$caja -> caja_fh_inicio.'" AND  "'.date('Y-m-d H:i:s').'"');
        $venta_anterior -> find();

        $numero_venta = $venta_anterior -> N + 1;
        // 1. Crea una nueva venta
        $venta = DB_DataObject::factory('venta');
        $venta -> venta_fh = date("Y-m-d H:i:s");
        $venta -> venta_usuario_id = $_SESSION['usuario']['id'];
        $venta -> venta_cliente_id= $objeto['cliente'];
        $venta -> venta_estado_id = 1; // Sin stock
        $venta -> venta_nro = $numero_venta; // 

        $id_venta = $venta -> insert();
        // 3. Asigna productos al detalle de la venta
        $total_venta = 0;
        foreach ($objeto['producto'] as $p) {
            $producto = DB_DataObject::factory('producto'); 
            $prod = $producto -> getProductos($p['id']);

            $restar_ok = $prod -> restarStock($p['cant'],$p['lote']);

            if($restar_ok) {        //si resto el stock
                $detalle = DB_DataObject::factory('venta_detalle');
                $detalle -> detalle_venta_id = $id_venta;
                $detalle -> detalle_prod_id = $p['id'];
                $detalle -> detalle_prod_cant = $p['cant'];
                $detalle -> detalle_prod_precio_u = $p['val'];
                $detalle -> detalle_prod_calibre = $p['calibre'];
                $detalle -> detalle_prod_lote = $p['lote'];
                $total_venta = $total_venta + $p['tot'] ;

                $det_id = $detalle -> insert();

                //Agrego el VDS
                    $venta_detalle_stock = DB_DataObject::factory('venta_detalle_stock');
                    $venta_detalle_stock -> vds_venta_detalle_id = $det_id; 
                    $venta_detalle_stock -> vds_prodstock_id = $p['lote'];
                    $venta_detalle_stock -> vds_prod_cant = $p['cant'];
                    $venta_detalle_stock -> insert();

            }

        }

        $venta_update = DB_DataObject::factory('venta');
        $venta_update -> venta_id = $id_venta;
        $venta_update -> find(true);

        $venta_update -> venta_monto_total = $total_venta;
        $venta_update -> update();

        $respuesta = array();
        // $respuesta['id'] = $id_venta;
        $respuesta['id'] = $numero_venta;

        $cliente = DB_DataObject::factory('cliente');
        $respuesta['nombre'] = $cliente -> getClientes($objeto['cliente']) -> cliente_nombre;
        $respuesta['monto'] = $total_venta;

        return $respuesta;
    }

    function getVenta($id) {
        $do_ventas = DB_DataObject::factory('venta');

        if($id){
            $do_ventas -> venta_id = $id;
        }

        $do_usuario = DB_DataObject::factory('usuario');
        $do_cliente = DB_DataObject::factory('cliente');
        $do_venta_estado = DB_DataObject::factory('venta_estado');
        $do_venta_forma_pago = DB_DataObject::factory('venta_forma_pago');

        $do_ventas -> joinAdd($do_usuario);
        $do_ventas -> joinAdd($do_cliente);
        $do_ventas -> joinAdd($do_venta_estado);
        $do_ventas -> joinAdd($do_venta_forma_pago);

        if($id){
            $do_ventas -> find(true);
        } else {
            $do_ventas -> whereAdd('venta_estado_id != 3');
            $do_ventas -> whereAdd('venta_baja_fh IS NULL AND venta_archivada_fh IS NULL') ;
            $do_ventas -> orderBy('venta_id DESC');
            $do_ventas -> find();
        }
        return $do_ventas;
    }

    function getVentasPendientes($desde = false,$hasta = false) {
        //DB_DataObject::debugLevel(1);
        $do_ventas = DB_DataObject::factory('venta');


        $do_usuario = DB_DataObject::factory('usuario');
        $do_cliente = DB_DataObject::factory('cliente');
        $do_venta_estado = DB_DataObject::factory('venta_estado');
        $do_venta_forma_pago = DB_DataObject::factory('venta_forma_pago');

        $do_ventas -> joinAdd($do_usuario,"LEFT");
        $do_ventas -> joinAdd($do_cliente,"LEFT");
        $do_ventas -> joinAdd($do_venta_estado,"LEFT");
        $do_ventas -> joinAdd($do_venta_forma_pago,"LEFT");

        if($desde && $hasta){
            $do_ventas -> whereAdd('venta_fh BETWEEN "'.$desde.'" AND "'.$hasta.'"');
        }

        $do_ventas -> whereAdd('venta_estado_id = 1 AND venta_baja_fh IS NULL') ;
        $do_ventas -> orderBy('venta_id DESC');
        $do_ventas -> find();

        return $do_ventas;
    }

     function getVentasSaldadas($desde = false,$hasta = false) {
        //DB_DataObject::debugLevel(1);
        $do_ventas = DB_DataObject::factory('venta');


        $do_usuario = DB_DataObject::factory('usuario');
        $do_cliente = DB_DataObject::factory('cliente');
        $do_venta_estado = DB_DataObject::factory('venta_estado');
        $do_venta_forma_pago = DB_DataObject::factory('venta_forma_pago');
        $do_ventas -> joinAdd($do_usuario,"LEFT");
        $do_ventas -> joinAdd($do_cliente,"LEFT");
        $do_ventas -> joinAdd($do_venta_estado,"LEFT");
        $do_ventas -> joinAdd($do_venta_forma_pago,"LEFT");

        if($desde && $hasta){
            $do_ventas -> whereAdd('venta_fh BETWEEN "'.$desde.'" AND "'.$hasta.'"');
        }

        $do_ventas -> whereAdd('venta_estado_id = 2 AND venta_baja_fh IS NULL ') ;
        $do_ventas -> orderBy('venta_id DESC');
        $do_ventas -> find();

        return $do_ventas;
    }

     function getVentasDespachadas($desde = false,$hasta = false) {
        //DB_DataObject::debugLevel(1);
        $do_ventas = DB_DataObject::factory('venta');


        $do_usuario = DB_DataObject::factory('usuario');
        $do_cliente = DB_DataObject::factory('cliente');
        $do_venta_estado = DB_DataObject::factory('venta_estado');
        $do_venta_forma_pago = DB_DataObject::factory('venta_forma_pago');

        $do_ventas -> joinAdd($do_usuario,"LEFT");
        $do_ventas -> joinAdd($do_cliente,"LEFT");
        $do_ventas -> joinAdd($do_venta_estado,"LEFT");
        $do_ventas -> joinAdd($do_venta_forma_pago,"LEFT");

        if($desde && $hasta){
            $do_ventas -> whereAdd('venta_fh BETWEEN "'.$desde.'" AND "'.$hasta.'"');
        }

        $do_ventas -> whereAdd('venta_estado_id = 4 AND venta_baja_fh IS NULL ') ;
        $do_ventas -> orderBy('venta_id DESC');
        $do_ventas -> find();

        return $do_ventas;
    }

      function getVentasArchivadas($desde = false,$hasta = false) {
        //DB_DataObject::debugLevel(1);
        $do_ventas = DB_DataObject::factory('venta');


        $do_usuario = DB_DataObject::factory('usuario');
        $do_cliente = DB_DataObject::factory('cliente');
        $do_venta_estado = DB_DataObject::factory('venta_estado');
        $do_venta_forma_pago = DB_DataObject::factory('venta_forma_pago');

        $do_ventas -> joinAdd($do_usuario,"LEFT");
        $do_ventas -> joinAdd($do_cliente,"LEFT");
        $do_ventas -> joinAdd($do_venta_estado,"LEFT");
        $do_ventas -> joinAdd($do_venta_forma_pago,"LEFT");

        if($desde && $hasta){
            $do_ventas -> whereAdd('venta_fh BETWEEN "'.$desde.'" AND "'.$hasta.'"');
        }

        $do_ventas -> whereAdd('venta_estado_id = 6 AND venta_baja_fh IS NULL ') ;
        $do_ventas -> orderBy('venta_id DESC');
        $do_ventas -> find();

        return $do_ventas;
    }
    function getVentas($desde = false,$hasta = false) {
        $do_ventas = DB_DataObject::factory('venta');


        $do_usuario = DB_DataObject::factory('usuario');
        $do_cliente = DB_DataObject::factory('cliente');
        $do_venta_estado = DB_DataObject::factory('venta_estado');
        $do_venta_forma_pago = DB_DataObject::factory('venta_forma_pago');

        $do_ventas -> joinAdd($do_usuario,"LEFT");
        $do_ventas -> joinAdd($do_cliente,"LEFT");
        $do_ventas -> joinAdd($do_venta_estado,"LEFT");
        $do_ventas -> joinAdd($do_venta_forma_pago,"LEFT");

        if($desde && $hasta){
            $do_ventas -> whereAdd('venta_fh BETWEEN "'.$desde.'" AND "'.$hasta.'"');
        }

        $do_ventas -> whereAdd('venta_baja_fh IS NULL') ;
        $do_ventas -> orderBy('venta_id DESC');
        $do_ventas -> find();

        return $do_ventas;
    }
    function getVentasTodas($desde = false,$hasta = false) {
        $do_ventas = DB_DataObject::factory('venta');


        $do_usuario = DB_DataObject::factory('usuario');
        $do_cliente = DB_DataObject::factory('cliente');
        $do_venta_estado = DB_DataObject::factory('venta_estado');
        $do_venta_forma_pago = DB_DataObject::factory('venta_forma_pago');

        $do_ventas -> joinAdd($do_usuario,"LEFT");
        $do_ventas -> joinAdd($do_cliente,"LEFT");
        $do_ventas -> joinAdd($do_venta_estado,"LEFT");
        $do_ventas -> joinAdd($do_venta_forma_pago,"LEFT");

        if($desde && $hasta){
            $do_ventas -> whereAdd('venta_fh BETWEEN "'.$desde.'" AND "'.$hasta.'"');
        }

        $do_ventas -> orderBy('venta_id DESC');
        $do_ventas -> find();

        return $do_ventas;
    }
    function getVentasFiadas($desde = false,$hasta = false) {
        $do_ventas = DB_DataObject::factory('venta');

        $do_usuario = DB_DataObject::factory('usuario');
        $do_cliente = DB_DataObject::factory('cliente');
        $do_venta_estado = DB_DataObject::factory('venta_estado');
        $do_venta_forma_pago = DB_DataObject::factory('venta_forma_pago');

        $do_ventas -> joinAdd($do_usuario,"LEFT");
        $do_ventas -> joinAdd($do_cliente,"LEFT");
        $do_ventas -> joinAdd($do_venta_estado,"LEFT");
        $do_ventas -> joinAdd($do_venta_forma_pago,"LEFT");

        if($desde && $hasta){
            $do_ventas -> whereAdd('venta_fh BETWEEN "'.$desde.'" AND "'.$hasta.'"');
        }

       $do_ventas -> whereAdd('venta_monto_total > venta_monto_contado AND venta_estado_id != 1') ;
        $do_ventas -> whereAdd('venta_baja_fh IS NULL') ;
        $do_ventas -> orderBy('venta_id DESC');
        $do_ventas -> find();

        return $do_ventas;
    }
    function getVentasAnuladas($desde = false,$hasta = false) {
        $do_ventas = DB_DataObject::factory('venta');

        $do_usuario = DB_DataObject::factory('usuario');
        $do_cliente = DB_DataObject::factory('cliente');
        $do_venta_estado = DB_DataObject::factory('venta_estado');
        $do_venta_forma_pago = DB_DataObject::factory('venta_forma_pago');

        $do_ventas -> joinAdd($do_usuario,"LEFT");
        $do_ventas -> joinAdd($do_cliente,"LEFT");
        $do_ventas -> joinAdd($do_venta_estado,"LEFT");
        $do_ventas -> joinAdd($do_venta_forma_pago,"LEFT");

        if($desde && $hasta){
            $do_ventas -> whereAdd('venta_fh BETWEEN "'.$desde.'" AND "'.$hasta.'"');
        }

        $do_ventas -> whereAdd('venta_baja_fh IS NOT NULL') ;
        $do_ventas -> orderBy('venta_id DESC');
        $do_ventas -> find();

        return $do_ventas;
    }
        function getVentasDevolucion($desde = false,$hasta = false) {
        $do_ventas = DB_DataObject::factory('venta');

        $do_usuario = DB_DataObject::factory('usuario');
        $do_cliente = DB_DataObject::factory('cliente');
        $do_venta_estado = DB_DataObject::factory('venta_estado');
        $do_venta_forma_pago = DB_DataObject::factory('venta_forma_pago');
        $do_devolucion = DB_DataObject::factory('devolucion_mercaderia');

        $do_ventas -> joinAdd($do_usuario,"LEFT");
        $do_ventas -> joinAdd($do_cliente,"LEFT");
        $do_ventas -> joinAdd($do_venta_estado,"LEFT");
        $do_ventas -> joinAdd($do_venta_forma_pago,"LEFT");


        if($desde && $hasta){
            $do_ventas -> whereAdd('venta_fh BETWEEN "'.$desde.'" AND "'.$hasta.'"');
        }

        $do_ventas -> orderBy('venta_id DESC');
        $do_ventas -> find();
        $do_devolucion -> joinAdd($do_ventas,"LEFT");
        $do_devolucion -> groupBy('venta_id');
        $do_devolucion -> find();

        return $do_devolucion;
    }
    function getDetalleString() {

        $detalle = DB_DataObject::factory('venta_detalle');
        $detalle -> detalle_venta_id = $this -> venta_id;
        $producto = DB_DataObject::factory('producto');
        $categoria = DB_DataObject::factory('categoria');

        $producto -> joinAdd($categoria);
        $detalle -> joinAdd($producto);
        $detalle -> find();

        $respuesta = '';
        $arreglo = array();

        while ($detalle -> fetch()) {
            $arreglo[$detalle -> detalle_prod_id]['nombre'] = $detalle -> prod_modelo;
            $arreglo[$detalle -> detalle_prod_id]['cant'] += $detalle -> detalle_prod_cant;
        }

        $i = 0;
        foreach ($arreglo as $prod) {
            if($i) {
                $respuesta .= ', ';
            }
            $respuesta .= ''.$prod['cant'].' '.$prod['nombre'];
            $i ++;
        }

        return $respuesta;
    }

    function getCantProd() {

        $detalle = DB_DataObject::factory('venta_detalle');
        $detalle -> detalle_venta_id = $this -> venta_id;
        $detalle -> find();
        

        return $detalle -> N;

    }

    function getCantBultos() {
       
        $detalle = DB_DataObject::factory('venta_detalle');
        $detalle -> detalle_venta_id =$this -> venta_id;
        $detalle -> find();

       while ($detalle -> fetch()) {
          $cantBultos = $cantBultos + $detalle -> detalle_prod_cant ;
        }
        
        return $cantBultos;
    }


    function editarVenta($objeto) {
        $venta = DB_DataObject::factory('venta');
        $venta -> venta_id = $objeto['edit_venta'];
        $venta -> find(true);

        $id_venta = $objeto['edit_venta'];

        $venta -> venta_fh = date("Y-m-d",strtotime($objeto['venta_fh']));
        $venta -> venta_usuario_id = $_SESSION['usuario']['id'];

        // Traigo todo el detalle 
        $det_old = DB_DataObject::factory('venta_detalle'); 
        $det_old -> detalle_venta_id = $venta -> venta_id;
        $det_old -> find();

        while ($det_old -> fetch()) {       //Restauro el stock
            $vds = DB_DataObject::factory('venta_detalle_stock');
            $vds -> vds_venta_detalle_id = $det_old -> detalle_id;
            $vds -> find();

            while ($vds -> fetch()) {
                $vds -> delete(); // Borro el venta_detalle_stock
            }

            $ps = DB_DataObject::factory('producto_stock');
            $ps -> ps_id = $det_old -> detalle_prod_lote;
            $ps -> find(true);
            $ps -> ps_cantidad = $ps -> ps_cantidad +  $det_old -> detalle_prod_cant;
            $ps -> update();
            $det_old -> delete();
        }
        // 3. Asigna productos al detalle de la venta
        $total_venta = 0;

        foreach ($objeto['producto'] as $p) {
            $producto = DB_DataObject::factory('producto'); 
            $restar_ok = $producto -> restarStock($p['cant'],$p['lote']);

            if($restar_ok) {
                $detalle = DB_DataObject::factory('venta_detalle');
                $detalle -> detalle_venta_id = $id_venta;
                $detalle -> detalle_prod_id = $p['id'];
                $detalle -> detalle_prod_cant = $p['cant'];
                $detalle -> detalle_prod_precio_u = $p['val'];
                $detalle -> detalle_prod_calibre = $p['calibre'];
                $detalle -> detalle_prod_lote = $p['lote'];
                if ($p['desc']) {
                $detalle -> detalle_descuento_parcial = $p['desc'];
                }
                
                
                $total_venta = $total_venta + $p['tot'] ;
  
                $det_id = $detalle -> insert();

                //Agrego el VDS
                    $venta_detalle_stock = DB_DataObject::factory('venta_detalle_stock');
                    $venta_detalle_stock -> vds_venta_detalle_id = $det_id; 
                    $venta_detalle_stock -> vds_prodstock_id = $p['lote'];
                    $venta_detalle_stock -> vds_prod_cant = $p['cant'];
                    $venta_detalle_stock -> insert();
            }
        }
        $venta -> venta_monto_total = $total_venta;
        $cliente = DB_DataObject::factory('cliente');
        $cliente -> getClientes($venta -> venta_cliente_id);

         if($venta -> update()) {
            $respuesta = array();
            $respuesta['total_venta'] = $total_venta;
            $respuesta['id_venta_edit'] = $venta -> venta_id;
            $respuesta['cliente_nombre'] = $cliente -> cliente_nombre;

            return $respuesta;

        }

    }

    function eliminarVenta($objeto) {   
        $venta = DB_DataObject::factory('venta');
        $venta -> venta_id = $objeto['edit_venta'];
        $venta -> find(true);

        $venta -> venta_baja_fh = date("Y-m-d H:i:s");
        $venta -> venta_estado_id = 3;

        // Traigo todo el detalle 
        $det_old = DB_DataObject::factory('venta_detalle'); 
        $det_old -> detalle_venta_id = $venta -> venta_id;
        $det_old -> find();

        while ($det_old -> fetch()) {
                $ps = DB_DataObject::factory('producto_stock');
                $ps -> ps_id = $det_old -> detalle_prod_lote;
                $ps -> find(true);
                $ps -> ps_cantidad = $ps -> ps_cantidad +  $det_old -> detalle_prod_cant;
                $ps -> update();
        }

        $venta -> update();
        return $venta -> venta_id;
        
    }

     function despacharVenta($id) {
        $venta = DB_DataObject::factory('venta');
        $venta -> venta_id = $id;
        $venta -> find(true);

        $producto_stock = DB_DataObject::factory('producto_stock');
        $producto_stock -> getPPVFinal();

        $venta -> venta_despachada_fh = date("Y-m-d H:i:s");
        $venta -> venta_estado_id = 4;
        $respuesta = $venta -> update();
        return $respuesta;
    }

    function  archivarVenta($id) {
        $venta = DB_DataObject::factory('venta');
        $venta -> venta_id = $id;
        $venta -> find(true);

        $venta -> venta_archivada_fh = date("Y-m-d H:i:s");
        $venta -> venta_estado_id = 6;
        $respuesta = $venta -> update();
        return $respuesta;
    }

    function getTotalVentasCaja($fi,$ff) {
        $this -> whereAdd('venta_estado_id != 3 and venta_fh BETWEEN "'.$fi.'" AND  "'.$ff.'"');
        $this -> find();

        return $this -> N;
    }

    // Funcion para caja actual
    function getBultosDiariosCaja($fi,$ff=false) {
        if(!$ff){
            $ff = date('Y-m-d H:i:s');
        }

        $total_bultos = 0;
        $bultos_despachados = 0;

        $do_venta_detalle = DB_DataObject::factory('venta_detalle');
        $this -> joinAdd($do_venta_detalle);

        $this -> whereAdd('venta_estado_id != 3 and venta_fh BETWEEN "'.$fi.'" AND  "'.$ff.'"');
        
        $this -> find();

        while($this -> fetch()){
            if($this -> venta_estado_id == 4) {
                $bultos_despachados += $this -> detalle_prod_cant;
            }
            $total_bultos += $this -> detalle_prod_cant;
        }

        $tv = DB_DataObject::factory('venta');
        $total = $tv -> getTotalVentasCaja($fi,$ff);

        $respuesta['Ventas'] = $total;
        $respuesta['Bultos_vendidos'] = $total_bultos;
        $respuesta['Bultos_despachados'] = $bultos_despachados;

        return $respuesta;

    }

    function getCantidadVentas($estado,$desde=false,$hasta=false) {
        $do_venta = DB_DataObject::factory('venta');
        if($desde AND $hasta){
            $do_venta -> whereAdd('venta_estado_id ='.$estado.' AND venta_fh BETWEEN "'.$desde.'" AND "'.$hasta.'"');
        }else{
            $do_venta -> whereAdd('venta_estado_id ='.$estado);
        }
        $do_venta -> find();

        return $do_venta -> N;
    }

    function getResumenCantidades() {
        //OBTENGO FECHA DE INCIO DE CAJA
        $do_caja = DB_DataObject::factory('caja');
        $caja = $do_caja -> getUltimaCaja();
        //DB_DataObject::debugLevel(5);
        $desde = $caja -> caja_fh_inicio;
        $hasta = date('Y-m-d H:i:s');
        

        $do_ventas = DB_DataObject::factory('venta');

        $cantidad_ventas['pendientes'] = $do_ventas -> getCantidadVentas(1,$desde,$hasta);
        $cantidad_ventas['saldadas'] = $do_ventas -> getCantidadVentas(2,$desde,$hasta);
        $cantidad_ventas['despachadas'] = $do_ventas -> getCantidadVentas(4,$desde,$hasta);
        
        $cantidad_ventas['anuladas'] = $do_ventas -> getCantidadVentas(3,$desde,$hasta);
        $cantidad_ventas['todas'] = $do_ventas -> getVentasTodas($desde,$hasta) -> N;
        $cantidad_ventas['fiado'] = $do_ventas -> getVentasFiadas($desde,$hasta) -> N;
        $cantidad_ventas['devolucion'] = $do_ventas -> getVentasDevolucion($desde,$hasta) -> N;

    return $cantidad_ventas;

    }


function editarCliente($idcliente,$idventa) {
    $do_venta = DB_DataObject::factory('venta');
    $do_venta -> venta_id = $idventa;
    $do_venta -> find(true);
    $do_venta -> venta_cliente_id = $idcliente;
    $respuesta = $do_venta -> update();
    return $respuesta;

    }



function getMontoTotalVentas($fi,$ff=false) {
    
    if(!$ff){
        $ff = date('Y-m-d H:i:s');
    }

    $do_venta = DB_DataObject::factory('venta');
    $do_venta -> whereAdd('venta_estado_id IN (2,4) AND venta_fh BETWEEN "'.$fi.'" AND "'.$ff.'"'); // PENDIENTES; SALDADAS; DESPACHADAS
    $do_venta -> find();

    $respuesta['Total'] = 0;

    while($do_venta -> fetch()){
        $respuesta['Total'] += $do_venta -> venta_monto_total;
    }

    return $respuesta['Total'];

    }


function getVentasCobroParcial($fi,$ff=false) {
    
    if(!$ff){
        $ff = date('Y-m-d H:i:s');
    }

    $do_venta = DB_DataObject::factory('venta');
    $do_venta -> whereAdd('venta_forma_pago = 1 AND venta_monto_contado < venta_monto_total AND venta_estado_id IN (1,2,4) AND venta_fh BETWEEN "'.$fi.'" AND "'.$ff.'"'); // PENDIENTES; SALDADAS; DESPACHADAS
    $do_venta -> find();

    $respuesta['Total'] = 0;

    while($do_venta -> fetch()){
        $respuesta['Total'] += $do_venta -> venta_monto_contado;
    }

    return $respuesta['Total'];

    }


function getMontoVentasACC($fi,$ff=false) {
    if(!$ff){
        $ff = date('Y-m-d H:i:s');
    }

    $do_venta = DB_DataObject::factory('venta');
    $do_venta -> whereAdd('venta_monto_contado < venta_monto_total AND venta_estado_id IN (2,4) AND venta_fh BETWEEN "'.$fi.'" AND "'.$ff.'"'); // SALDADAS; DESPACHADAS; FORMA DE PAGO A CC
    $do_venta -> find();

    $respuesta['Total'] = 0;

    while($do_venta -> fetch()){
        $respuesta['Total'] += $do_venta -> venta_monto_total - $do_venta -> venta_monto_contado;
    }

    return $respuesta['Total'];

    }

function reporteOnline() {
    // traigo todas la ventas de la caja

    // Estado actual de la caja
    $do_caja = DB_DataObject::factory('caja');
    $caja = $do_caja -> getUltimaCaja();
   // DB_DataObject::debugLevel(5);
    $desde = $caja -> caja_fh_inicio;
    $hasta = date('Y-m-d H:i:s');

    $venta = DB_DataObject::factory('venta');
    $venta_detalle = DB_DataObject::factory('venta_detalle');

    $producto = DB_DataObject::factory('producto');
    $producto_stock = DB_DataObject::factory('producto_stock');
    $categoria = DB_DataObject::factory('categoria');
    $do_tipo = DB_DataObject::factory('tipo');
    
    $venta_detalle -> joinAdd($producto_stock);
    $venta_detalle -> joinAdd($venta);
    $categoria -> joinAdd($do_tipo);
    $producto -> joinAdd($categoria);

    $venta_detalle -> joinAdd($producto);

    $venta_detalle -> whereAdd('(venta_estado_id = 2 or venta_estado_id = 4)  AND venta_fh BETWEEN "'.$desde.'" AND "'.$hasta.'"');
    $venta_detalle -> find();
    $respuesta = array();
    while($venta_detalle -> fetch()){

        $respuesta[$venta_detalle -> tipo_nombre][$venta_detalle -> cat_nombre][$venta_detalle -> prod_alias.' '.$venta_detalle -> ps_calibre]['stock'] = $producto -> getStockInforme($venta_detalle -> detalle_prod_id, $venta_detalle -> detalle_prod_calibre,$desde,$hasta);
        $respuesta[$venta_detalle -> tipo_nombre][$venta_detalle -> cat_nombre][$venta_detalle -> prod_alias.' '.$venta_detalle -> ps_calibre]['bultos'] += $venta_detalle -> detalle_prod_cant;  
         $respuesta[$venta_detalle -> tipo_nombre][$venta_detalle -> cat_nombre][$venta_detalle -> prod_alias.' '.$venta_detalle -> ps_calibre]['id'] = $venta_detalle -> detalle_prod_id; 
         $respuesta[$venta_detalle -> tipo_nombre][$venta_detalle -> cat_nombre][$venta_detalle -> prod_alias.' '.$venta_detalle -> ps_calibre]['calibre'] = $venta_detalle -> detalle_prod_calibre; 
        
        if(($venta_detalle -> detalle_prod_precio_u > $respuesta[$venta_detalle -> tipo_nombre][$venta_detalle -> cat_nombre][$venta_detalle -> prod_alias.' '.$venta_detalle -> ps_calibre]['precio_mayor']) OR ($respuesta[$venta_detalle -> tipo_nombre][$venta_detalle -> cat_nombre][$venta_detalle -> prod_alias.' '.$venta_detalle -> ps_calibre]['precio_mayor'] == "")) {
            $respuesta[$venta_detalle -> tipo_nombre][$venta_detalle -> cat_nombre][$venta_detalle -> prod_alias.' '.$venta_detalle -> ps_calibre]['precio_mayor'] = $venta_detalle -> detalle_prod_precio_u;
        }
        
        if($venta_detalle -> detalle_prod_precio_u < $respuesta[$venta_detalle -> tipo_nombre][$venta_detalle -> cat_nombre][$venta_detalle -> prod_alias.' '.$venta_detalle -> ps_calibre]['precio_menor'] OR $respuesta[$venta_detalle -> tipo_nombre][$venta_detalle -> cat_nombre][$venta_detalle -> prod_alias.' '.$venta_detalle -> ps_calibre]['precio_menor'] == "") {
            $respuesta[$venta_detalle -> tipo_nombre][$venta_detalle -> cat_nombre][$venta_detalle -> prod_alias.' '.$venta_detalle -> ps_calibre]['precio_menor'] = $venta_detalle -> detalle_prod_precio_u;
        }

        $respuesta[$venta_detalle -> tipo_nombre][$venta_detalle -> cat_nombre][$venta_detalle -> prod_alias.' '.$venta_detalle -> ps_calibre]['acum_precio'] += $venta_detalle -> detalle_prod_cant * $venta_detalle -> detalle_prod_precio_u;

        if($respuesta[$venta_detalle -> tipo_nombre][$venta_detalle -> cat_nombre][$venta_detalle -> prod_alias.' '.$venta_detalle -> ps_calibre]['acum_precio'] != 0){
            $respuesta[$venta_detalle -> tipo_nombre][$venta_detalle -> cat_nombre][$venta_detalle -> prod_alias.' '.$venta_detalle -> ps_calibre]['promedio'] = $respuesta[$venta_detalle -> tipo_nombre][$venta_detalle -> cat_nombre][$venta_detalle -> prod_alias.' '.$venta_detalle -> ps_calibre]['acum_precio'] / $respuesta[$venta_detalle -> tipo_nombre][$venta_detalle -> cat_nombre][$venta_detalle -> prod_alias.' '.$venta_detalle -> ps_calibre]['bultos'];
        }

    }
    return $respuesta;
}
}