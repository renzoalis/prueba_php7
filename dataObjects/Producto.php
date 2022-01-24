<?php
/**
 * Table Definition for producto
 */
require_once 'DB/DataObject.php';

class DataObjects_Producto extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'producto';            // table name
    public $prod_id;                         // int(11)  not_null primary_key auto_increment group_by
    public $prod_nombre;                     // varchar(255)  not_null
    public $prod_alias;                      // varchar(255)  not_null
    public $prod_codigo;                     // int(11)  not_null group_by
    public $prod_origen;                     // varchar(255)  not_null
    public $prod_presentacion;               // varchar(255)  not_null
    public $prod_baja;                       // tinyint(1)  not_null group_by
    public $prod_cat_id;                     // int(11)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Producto',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    
    function getProductos($id = false){
        $do_productos = DB_DataObject::factory('producto');
        
        if($id){
            $do_productos -> whereAdd('prod_id = '.$id.' AND prod_baja = 0');
        }else{
            $do_productos -> whereAdd('prod_baja = 0');
        }

        $do_categoria = DB_DataObject::factory('categoria');
        $do_tipo = DB_DataObject::factory('tipo');

        $do_categoria -> joinAdd($do_tipo);
        $do_productos -> joinAdd($do_categoria);

        if($id){
            $do_productos -> whereAdd('prod_id = '.$id.' AND prod_baja = 0');
            $do_productos -> find(true);
            $do_productos -> prod_stock_cantidad = $do_productos -> getStock();
        } else {
            $do_productos -> whereAdd('prod_baja = 0');
            $do_productos -> find();
        }

        return $do_productos;  
    }

    function getProductosConStock(){
        $do_productos = DB_DataObject::factory('producto');
        $do_categoria = DB_DataObject::factory('categoria');
        $do_tipo = DB_DataObject::factory('tipo');
        $do_producto_stock = DB_DataObject::factory('producto_stock');

        $do_categoria -> joinAdd($do_tipo);
        $do_productos -> joinAdd($do_categoria);
        $do_productos -> joinAdd($do_producto_stock);

        $do_productos -> whereAdd('prod_baja = 0 and (ps_cantidad > 0 OR ps_precio_prom_venta_parcial != 0)');
        $do_productos -> find();
        

        return $do_productos;  
    }

     function getProductosTransf(){
        // DB_DataObject::debugLevel(1);
        $do_producto_stock = DB_DataObject::factory('producto_stock');
        $do_productos = DB_DataObject::factory('producto');
        $do_categoria = DB_DataObject::factory('categoria');
        $do_tipo = DB_DataObject::factory('tipo');

        $do_compra = DB_DataObject::factory('compra');
        $do_compra_detalle = DB_DataObject::factory('compra_detalle');
        $do_transferencias = DB_DataObject::factory('transferencias');
        $flete = DB_DataObject::factory('flete');
        $flete_detalle = DB_DataObject::factory('flete_detalle');
        
        $descarga = DB_DataObject::factory('descarga');
        $descarga_detalle = DB_DataObject::factory('descarga_detalle');
        
        $do_categoria -> joinAdd($do_tipo,"LEFT");
        $do_productos -> joinAdd($do_categoria,"LEFT");
        $do_producto_stock -> joinAdd($do_productos,"LEFT");
        $do_compra -> joinAdd($do_compra_detalle,"LEFT");
        $descarga -> joinAdd($descarga_detalle,"LEFT");
        $do_compra -> joinAdd($descarga,"LEFT");
        $flete -> joinAdd($flete_detalle,"LEFT");
        $do_compra -> joinAdd($flete,"LEFT");
        $do_compra -> find();

        $do_producto_stock -> joinAdd($do_compra,"LEFT");
        $do_producto_stock -> joinAdd($do_transferencias,"LEFT");

        //PARA PODER TRANSFERIR UN PRODUCTO TIENE QUE TENER TODOS LOS COSTOS CARGADOS (COSTO UNITARIO, COSTO DESCARGA, Y COSTO FLETE) O BIEN VENIR DE UNA TRANSFERENCIA (YA VIENE CON TODOS LOS COSTOS CARGADOS DE PUESTO ORIGEN)
        $do_producto_stock -> whereAdd('(ps_cantidad > 0) AND ((ps_compra_id IS NOT NULL AND compra_concepto_fletes IS NOT NULL AND compra_concepto_descargas IS NOT NULL and detalle_prod_precio_u IS NOT NULL) OR (ps_transf_id IS NOT NULL))');
        $do_producto_stock -> find();

        return $do_producto_stock;  
    }

    // Devuelve cantidad de productos sin precio.
    function getProductosSinPrecio(){
        $do_productos = DB_DataObject::factory('producto');
        $do_productos -> prod_precio = 0;
        $do_productos -> find();
        return $do_productos -> N;  
    }

    function nuevoProducto($objeto) {

        $do_producto = DB_DataObject::factory('producto');
        $do_producto -> prod_nombre = $objeto['input_modelo'];
         if($objeto['input_alias']){
            $do_producto -> prod_alias = $objeto['input_alias'];
        }else{
             $do_producto -> prod_alias = $objeto['input_modelo'];;
        }
        $do_producto -> prod_cat_id = $objeto['input_categoria'];
        $do_producto -> prod_baja = 0;
        $do_producto -> prod_presentacion = $objeto['input_presentacion'];

        $id_insert = $do_producto -> insert();

        return $id_insert;
    }

    function modificarProducto($objeto) {

        $do_producto = DB_DataObject::factory('producto');
        $do_producto -> prod_id = $objeto['edit_producto_id'];
        $do_producto -> find(true);

        $do_producto -> prod_cat_id = $objeto['input_categoria'];
        $do_producto -> prod_nombre = $objeto['input_modelo_edit'];
        $do_producto -> prod_alias = $objeto['input_alias_edit'];
        $do_producto -> prod_proveedor_id = $objeto['input_proveedor'];
        $do_producto -> prod_precio = $objeto['input_precio_contado'];
        $do_producto -> prod_cantidad = $objeto['input_stock'];

        $id_update = $do_producto -> update();
        return $id_update;
    }

    function restarStock_old($cant,$calibre) {
        $id = false;
        $costo_total = 0;
        $cantidad = $cant;
        $respuesta = array();
        $do_producto_stock = DB_DataObject::factory('producto_stock');
        //$do_producto_stock -> ps_producto_id = $this -> prod_id;
        $do_producto_stock -> whereAdd('ps_cantidad > 0 AND ps_producto_id ='.$this -> prod_id.' AND ps_calibre = "'.$calibre.'"');

        $do_producto_stock -> find();
        while ($do_producto_stock -> fetch()) {
            if($do_producto_stock -> ps_cantidad >= $cant) {
                $do_producto_stock -> ps_cantidad = $do_producto_stock -> ps_cantidad - $cant;
                $id = $do_producto_stock -> update();
                $respuesta['productos'][$do_producto_stock -> ps_id] = $cant;
                $cant = 0;
                break;
            } else { 
                $cant = $cant - $do_producto_stock -> ps_cantidad; 
                $respuesta['productos'][$do_producto_stock -> ps_id] = $do_producto_stock -> ps_cantidad;
                $do_producto_stock -> ps_cantidad = 0;
                $do_producto_stock -> update();
            }
        }
        // if($cant){ // NO HAY STOCK

        // }
        if($id){
            return $respuesta;
        } else {
            return false;
        }
    }

    function restarStock($cant,$lote) {
        $do_producto_stock = DB_DataObject::factory('producto_stock');
        $do_producto_stock -> ps_id = $lote;
        $do_producto_stock -> find(true);

        if($do_producto_stock -> ps_cantidad >= $cant) {
            $do_producto_stock -> ps_cantidad = $do_producto_stock -> ps_cantidad - $cant;
            $id = $do_producto_stock -> update();
        } 

        return $id;
    }

    function restarStockXLote($cant,$lote) {

        $do_producto_stock = DB_DataObject::factory('producto_stock');
        $do_producto_stock -> whereAdd('ps_id='.$lote);
        $do_producto_stock -> find(true);
        
        if($do_producto_stock -> ps_cantidad >= $cant){
            $do_producto_stock -> ps_cantidad -= $cant;
            $id = $do_producto_stock -> update();
        }

        if($id){
            $respuesta['id'] = $do_producto_stock -> ps_id;
            $respuesta['costou'] = $do_producto_stock -> ps_costo_u;
            if($do_producto_stock -> ps_transf_id){         //Si el lote fue cargado desde una transf lo informo.
                $respuesta['lote_desc'] = $do_producto_stock -> ps_lote;
            }else{                                          //Si se creo a partir de una compra no.
                $respuesta['lote_desc'] = "";
            }
            return $respuesta;
        }else{
            return false;
        }
    }

    function sumarStock($compra_id, $producto_id, $costo_u, $pantidad, $calibre) {
        $id = false;

        $do_producto_stock = DB_DataObject::factory('producto_stock');
        $do_producto_stock -> ps_compra_id = $compra_id;
        $do_producto_stock -> ps_producto_id = $producto_id;
        $do_producto_stock -> ps_costo_u = $costo_u;
        $do_producto_stock -> ps_calibre = $calibre;
        $do_producto_stock -> ps_cantidad = $pantidad;

        $id = $do_producto_stock -> insert();

        if($id){
            $do_producto_stock = DB_DataObject::factory('producto_stock');
            $do_producto_stock -> ps_id = $id;
            $do_producto_stock -> find(true);

            $do_producto_stock -> ps_lote = PUESTO_CODIGO."00".$do_producto_stock -> ps_id;
            $do_producto_stock -> update();

            return $id;
        } else {
            return false;
        }

    }

    function sumarStockTransferencia($transf_id, $producto_id, $calibre, $cantidad, $costo_u, $detalle_lote) {
        $id = false;

        $do_producto_stock = DB_DataObject::factory('producto_stock');
        $do_producto_stock -> ps_transf_id = $transf_id;
        $do_producto_stock -> ps_producto_id = $producto_id;
        $do_producto_stock -> ps_costo_u = $costo_u;
        $do_producto_stock -> ps_calibre = $calibre;
        $do_producto_stock -> ps_cantidad = $cantidad;
        $do_producto_stock -> ps_lote = $detalle_lote;

        $id = $do_producto_stock -> insert();

        if($id){
            return $id;
        } else {
            return false;
        }

    }

    function restarStockTransferencia($producto_id, $cant) {
        $respuesta = array();
        $do_producto_stock = DB_DataObject::factory('producto_stock');
        $do_producto_stock -> ps_producto_id = $producto_id;
        $do_producto_stock -> whereAdd('ps_cantidad > 0');
        $do_producto_stock -> find();

        while ($do_producto_stock -> fetch()) {
            if($do_producto_stock -> ps_cantidad >= $cant) {
                $do_producto_stock -> ps_cantidad = $do_producto_stock -> ps_cantidad - $cant;
                $id = $do_producto_stock -> update();
                $respuesta['productos'][$do_producto_stock -> ps_id] = $cant;
                $cant = 0;
                break;
            } else { 
                $cant = $cant - $do_producto_stock -> ps_cantidad; 
                $respuesta['productos'][$do_producto_stock -> ps_id] = $do_producto_stock -> ps_cantidad;
                $do_producto_stock -> ps_cantidad = 0;
                $do_producto_stock -> update();
            }
        }
        if($id){
            return $respuesta;
        } else {
            return false;
        }

    }

    function getproductoCompra($id = false){
        $do_productos = DB_DataObject::factory('producto');
        $do_productos -> prod_baja = 0;
        if($id){
            $do_productos -> prod_id = $id;
        }

        $do_categoria = DB_DataObject::factory('categoria');
        $do_tipo = DB_DataObject::factory('tipo');

        $do_categoria -> joinAdd($do_tipo);
        $do_productos -> joinAdd($do_categoria);

        if($id){
            $do_productos -> find(true);
        } else {
            $do_productos -> find();
        }
        return $do_productos;  
    }

    function actualizarProveedor($id) {
        $this -> prod_proveedor_id = $id;
        $this -> update();
    }

    function getStock($calibre) {
        $do_producto_stock = DB_DataObject::factory('producto_stock');
        if($calibre){
                $do_producto_stock -> whereAdd('ps_producto_id = '.$this -> prod_id.' and  ps_cantidad > 0 AND ps_calibre = "'.$calibre.'"');
        }else{      //Si no especifica el calibre devuelvo el stock total solo por id prod
            $do_producto_stock -> whereAdd('ps_producto_id = '.$this -> prod_id.' and  ps_cantidad > 0');
        }
        
        $do_producto_stock -> find();

        $sum = 0;
        while ($do_producto_stock -> fetch()) {
            $sum += $do_producto_stock -> ps_cantidad;            
        }

        return $sum;
    }

    function prodConStock($cat_id=false){
        $do_ps = DB_DataObject::factory('producto_stock');

        $do_ps -> joinAdd($this);

        // $do_ps -> selectAdd();
        // $do_ps -> selectAdd('DISTINCT prod_id, prod_alias, ps_cantidad');
        
        if($cat_id){
            $do_ps -> whereAdd('ps_cantidad > 0 and prod_cat_id ='.$cat_id);
        }else{
            $do_ps -> whereAdd('ps_cantidad > 0');
        }

        $do_ps -> find();
        return($do_ps);
    }

    function getStockXLote($id) {
        $do_producto_stock = DB_DataObject::factory('producto_stock');
        $do_producto_stock -> ps_id = $id;
        $do_producto_stock -> find(true);

        return $do_producto_stock -> ps_cantidad;;
    }
     function getStockXLoteycalibre($id,$calibre) {
        $do_producto_stock = DB_DataObject::factory('producto_stock');
        $do_producto_stock -> ps_producto_id = $id;
         $do_producto_stock -> ps_calibre = $calibre;
        $do_producto_stock -> find(true);

        return $do_producto_stock -> ps_cantidad;;
    }

    function getStockInforme($id,$calibre,$desde,$hasta) {

        $respuesta = array();

        $respuesta['Inicial'] = 0; 
        $respuesta['Agregado'] = 0; 
        $respuesta['Transferido'] = 0; 
        $respuesta['Venta'] = 0;
        $respuesta['Recibido'] = 0;
        $respuesta['Transferido'] = 0;
        $respuesta['Compras'] = 0;
        $respuesta['Stock_vendido'] = 0;
        $respuesta['Stock_devuelto']= 0;

        $do_producto_stock = DB_DataObject::factory('producto_stock');
        $do_producto_stock -> ps_producto_id = $id;
        $do_producto_stock -> ps_calibre = $calibre;
        $do_producto_stock -> find();
        while($do_producto_stock -> fetch()){
            $respuesta['Venta'] += $do_producto_stock -> ps_cantidad;
        }

        $do_transferencias = DB_DataObject::factory('transferencias');
        $do_transferencias_detalle = DB_DataObject::factory('transferencias_detalle');
        $do_transferencias_detalle -> joinAdd($do_transferencias);

        $do_transferencias_detalle -> whereAdd('transf_fh BETWEEN "'.$desde.'" AND "'.$hasta.'" AND detalle_producto_id = '.$id.' AND detalle_calibre = "'.$calibre.'"');    //Enviadas en el dia
        $do_transferencias_detalle -> find();

        while($do_transferencias_detalle -> fetch()){
            if($do_transferencias_detalle -> transf_tipo == 1 ){
                $respuesta['Transferido'] +=  $do_transferencias_detalle -> detalle_producto_cantidad_origen; // Enviadas
            }elseif($do_transferencias_detalle -> transf_tipo == 2 ){
                $respuesta['Recibido'] +=  $do_transferencias_detalle -> detalle_producto_cantidad_destino; // Recibido
            }
        }


        $do_compras = DB_DataObject::factory('compra');
        $do_compras_detalle = DB_DataObject::factory('compra_detalle');

        $do_compras_detalle -> joinAdd($do_compras);

        $do_compras_detalle -> whereAdd('compra_fh BETWEEN "'.$desde.'" AND "'.$hasta.'" AND detalle_prod_id = '.$id.' AND detalle_prod_calibre = "'.$calibre.'"');    //compradas en el dia
        $do_compras_detalle -> find();

        while($do_compras_detalle -> fetch()){
            $respuesta['Compras'] += $do_compras_detalle -> detalle_prod_cant - $do_compras_detalle -> detalle_prod_dev;
        }   

        $respuesta['Agregado'] = $respuesta['Recibido'] + $respuesta['Compras'];


        $do_ventas = DB_DataObject::factory('venta');
        $do_ventas_detalle = DB_DataObject::factory('venta_detalle');

        $do_ventas_detalle -> joinAdd($do_ventas);
        $do_ventas_detalle -> whereAdd('venta_fh BETWEEN "'.$desde.'" AND "'.$hasta.'" AND detalle_prod_id = '.$id.' AND detalle_prod_calibre = "'.$calibre.'"');
        $do_ventas_detalle -> find();

        while($do_ventas_detalle -> fetch()){
            $respuesta['Stock_vendido'] += $do_ventas_detalle -> detalle_prod_cant - $do_ventas_detalle -> detalle_cant_dev;
            $respuesta['Stock_devuelto'] += $do_ventas_detalle -> detalle_cant_dev;
        }   

    //DB_DataObject::debugLevel(1);
        $conciliacion = DB_DataObject::factory('conciliacion');
        $conciliacion -> orderBy('c_id DESC');
        $conciliacion -> find(true);

        $conciliacion_detalle = DB_DataObject::factory('conciliacion_detalle');
        $conciliacion_detalle -> whereAdd('detalle_conc_id = '.$conciliacion -> c_id.' AND detalle_producto_id = '.$id.' AND detalle_calibre = "'.$calibre.'"');
        $conciliacion_detalle -> find();

        while ($conciliacion_detalle -> fetch()) {
            $respuesta['Inicial'] += $conciliacion_detalle -> detalle_prod_cant_venta;
        }

        // $respuesta['Inicial'] = $conciliacion_detalle -> count('detalle_prod_cant_venta');

        return $respuesta;
    }


    function getMenorStock() {

        $respuesta = array();
        $stock = array();
        $productos = array();

        $categoria = DB_DataObject::factory('categoria');
        $tipo = DB_DataObject::factory('tipo');

        $categoria -> joinAdd($tipo);

        $this -> whereAdd('prod_baja = 0');
        $this -> joinAdd($categoria);
        $this -> find();

        while($this -> fetch()){ 
            $productos[$this -> prod_id]['nombre'] = $this -> tipo_desc . ' ' .$this -> cat_nombre . ' ' . $this -> prod_modelo;
            $productos[$this -> prod_id]['cantidad'] = $this -> getStock();
            $stock[$this -> prod_id] = $this -> getStock();
        }

        asort($stock);
        
        $i=1;
        foreach ($stock as $key => $value) {
            $respuesta['nombre'][$i] = $productos[$key]['nombre'];
            $respuesta['cantidad'][$i] = $value; 
            $i++;
        }
        
        return $respuesta;
    }


    function getTipo($id) {
        $do_tipo = DB_DataObject::factory('tipo');
        $do_tipo -> tipo_id = $id;

        $do_tipo -> find(true);


        return utf8_decode($do_tipo -> tipo_desc);
    }

    function getCostoUnitario($id_transf, $id, $calibre) {
       
        $do_producto_stock = DB_DataObject::factory('producto_stock');
        $do_producto_stock -> whereAdd('ps_producto_id = '.$id.' and ps_calibre = "'.$calibre.'" and ps_transf_id = '.$id_transf);

        $do_producto_stock -> find(true);
        $costo_unitario = $do_producto_stock -> ps_costo_u;

        return $costo_unitario;
    }


    function getStockPorCalibre() {
        $do_producto_stock = DB_DataObject::factory('producto_stock');
        $do_producto_stock -> ps_producto_id = $this -> prod_id;
        $do_producto_stock -> ps_cantidad > 0;
        $do_producto_stock -> find();

        while ($do_producto_stock -> fetch()) {
            $sum[$do_producto_stock -> ps_calibre] += $do_producto_stock -> ps_cantidad;            
        }

        $sep = '';
        foreach ($sum as $key => $value) {
            $respuesta['calibres'] .= $sep.$key;
            $respuesta['cantidades'] .= $sep.$value;
            $sep = ', ';
        }

        return $respuesta;
    }

    
     function getStockFisico() {
        $do_producto_stock = DB_DataObject::factory('producto_stock');
        $do_producto_stock -> ps_producto_id = $this -> prod_id;
        $do_producto_stock -> ps_cantidad > 0;
        $do_producto_stock -> find();

        $sum = 0;
        while ($do_producto_stock -> fetch()) {
            $sum += $do_producto_stock -> ps_cantidad;            
        }
        //Busco el stock vendido, pero que todavia no se despacho para sumarlo y llegar al stock fisico
        $do_ventas = DB_DataObject::factory('venta');
        $do_venta_detalle = DB_DataObject::factory('venta_detalle');
        $do_ventas -> joinAdd($do_venta_detalle);


        $do_ventas -> whereAdd('venta_estado_id IN (1,2) AND detalle_prod_id='.$this -> prod_id);
        $do_ventas -> find();
        $fisico=0;

        while($do_ventas -> fetch()){
            $fisico += $do_ventas -> detalle_prod_cant;
        }
        
        $sum = $sum + $fisico;

        return $sum;
    }


    function getUltimosIds() {
        
        $this -> orderBy('prod_id DESC');
        $this -> find(true);


        $respuesta ['prod_id'] = $this -> prod_id;
        
        $do_categoria = DB_DataObject::factory('categoria');
        $do_categoria -> orderBy('cat_id DESC');
        $do_categoria -> find(true);

        $respuesta ['cat_id'] = $do_categoria -> cat_id;

        $do_tipo = DB_DataObject::factory('tipo');
        $do_tipo -> orderBy('tipo_id DESC');
        $do_tipo -> find(true);

        $respuesta ['tipo_id'] = $do_tipo -> tipo_id;
        
      
        return $respuesta;
    }



    function stockAgregado($fi=false,$ff=false){
        if(!$ff){
            $ff = date('Y-m-d H:i:s');
        }
        $producto = DB_DataObject::factory('producto');
        $categoria = DB_DataObject::factory('categoria');
        $tipo = DB_DataObject::factory('tipo');

        $producto_stock = DB_DataObject::factory('producto_stock');
        $compra = DB_DataObject::factory('compra');
        $proveedor = DB_DataObject::factory('proveedor');
        $transferencias = DB_DataObject::factory('transferencias');
        
        
        $compra -> joinAdd($proveedor,"LEFT");
        $categoria -> joinAdd($tipo);
        $producto -> joinAdd($categoria);
        $producto_stock -> joinAdd($producto);
        $producto_stock -> joinAdd($compra,"LEFT");
        $producto_stock -> joinAdd($transferencias,"LEFT");

        $fecha_hasta = new DateTime();  
        $f_desde =  $fecha_hasta -> modify("-1 day");

        if(!$fi){
            $producto_stock -> whereAdd(' (compra_fh BETWEEN "'.$f_desde -> format('Y-m-d H:i:s').'" AND "'.date('Y-m-d H:i:s').'") OR (transf_fh BETWEEN "'.$f_desde -> format('Y-m-d H:i:s').'" AND "'.date('Y-m-d H:i:s').'")');
            $campoFecha = date_format($f_desde,'d/m/Y').' - '.date('d/m/Y');

        } else {
            $producto_stock -> whereAdd('(compra_fh BETWEEN "'.$fi.'" AND "'.$ff.'") OR (transf_fh BETWEEN "'.$fi.'" AND "'.$ff.'") ' );
            $campoFecha = date('d/m/Y',strtotime($fi)).' - '.date('d/m/Y',strtotime($ff));
        }

        $producto_stock -> find();

        $respuesta = array();
        while($producto_stock -> fetch()){
            //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            /////////////////////////////////           DATOS DE LAS VENTAS                         //////////////////////////////////////////////
            /////////////////////////////////                                                       //////////////////////////////////////////////
            //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            $venta = DB_DataObject::factory('venta');
            $venta_detalle = DB_DataObject::factory('venta_detalle');
            
            $venta_detalle -> joinAdd($venta,"LEFT");
            if(!$fi){
                $venta_detalle -> whereAdd('venta_fh BETWEEN "'.$f_desde -> format('Y-m-d H:i:s').'" AND "'.date('Y-m-d H:i:s').'" AND detalle_prod_lote = '.$producto_stock -> ps_id.' AND venta_estado_id IN(1,2,4,6)'); //pendiente,saldada,desp,arch
            }else{
                $venta_detalle -> whereAdd('venta_fh BETWEEN "'.$fi.'" AND "'.$ff.'" AND detalle_prod_lote = '.$producto_stock -> ps_id.' AND venta_estado_id IN(1,2,4,6)'); //pendiente,saldada,desp,arch
            }

            $venta_detalle -> find();
            //print_r($venta_detalle);exit;
            $producto_stock -> cantidad_vendida_sin_retirar = 0;
            $producto_stock -> cantidad_devuelta = 0;
            $producto_stock -> cantidad_vendida = 0;
            $producto_stock -> cantidad_perdida = 0;
            //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            while($venta_detalle -> fetch()){

                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                /////////////////////////////////           DEVOLUCION DE MERCADERIA                    //////////////////////////////////////////////
                /////////////////////////////////                                                       //////////////////////////////////////////////
                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                $producto_stock -> cantidad_vendida += $venta_detalle -> detalle_prod_cant;

                $devolucion_mercaderia = DB_DataObject::factory('devolucion_mercaderia');
                $devolucion_mercaderia -> whereAdd('dev_venta_detalle_id = '.$venta_detalle -> detalle_id.' AND dev_prod_id = '.$venta_detalle -> detalle_prod_id);
                $devolucion_mercaderia -> find();

                while($devolucion_mercaderia -> fetch()){
                    if($devolucion_mercaderia -> dev_rest_stock){   // Con restitucion de stock
                 // if($producto_stock -> ps_id == 55){print_r($devolucion_mercaderia);exit;}
                        $producto_stock -> cantidad_devuelta += $devolucion_mercaderia -> dev_cantidad;
                    }else{ // PERDIDA
                        $producto_stock -> cantidad_perdida += $devolucion_mercaderia -> dev_cantidad;
                    }
                }
                
                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                if($venta_detalle -> venta_estado_id == "1" or $venta_detalle -> venta_estado_id == "2" ){
                    $producto_stock -> cantidad_vendida_sin_retirar += $venta_detalle -> detalle_prod_cant; 
                }

            }
            


                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                /////////////////////////////////           TRANSFERENCIAS                              //////////////////////////////////////////////
                /////////////////////////////////                                                       //////////////////////////////////////////////
                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            $transferencias = DB_DataObject::factory('transferencias');
            $transferencias_detalle = DB_DataObject::factory('transferencias_detalle');
            $transferencias_detalle -> joinAdd($transferencias);

            $transferencias_detalle -> whereAdd('detalle_lote = '.$producto_stock -> ps_id. ' AND transf_tipo = 1'); //Enviadas y del lote, aceptadas con y sin dif de stock
            $transferencias_detalle -> find();
            $producto_stock -> cantidad_transferida = 0;
            $producto_stock -> cantidad_transferida_destino = 0;
            while($transferencias_detalle -> fetch()){
                    $producto_stock -> cantidad_transferida += $transferencias_detalle -> detalle_producto_cantidad_origen;     
                    $producto_stock -> cantidad_transferida_destino += $transferencias_detalle -> detalle_producto_cantidad_destino;     

            }
            //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


            //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            /////////////////////////////////           ARMADO DEL ARRAY                            //////////////////////////////////////////////
            /////////////////////////////////                                                       //////////////////////////////////////////////
            //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            $respuesta[$producto_stock -> ps_id]['fecha_compra'] = $producto_stock -> compra_fh;
            $respuesta[$producto_stock -> ps_id]['fecha_tranferencia'] = $producto_stock -> transf_fh; 
            $respuesta[$producto_stock -> ps_id]['ps_id'] =   $producto_stock -> ps_id; 
            $respuesta[$producto_stock -> ps_id]['ps_lote'] =   $producto_stock -> ps_lote; 
            $respuesta[$producto_stock -> ps_id]['prov_nombre'] =   $producto_stock -> prov_nombre; 
            $respuesta[$producto_stock -> ps_id]['tipo_nombre'] =   $producto_stock -> tipo_nombre; 
            $respuesta[$producto_stock -> ps_id]['cat_nombre'] =    $producto_stock -> cat_nombre; 
            $respuesta[$producto_stock -> ps_id]['prod_alias'] =    $producto_stock -> prod_alias; 
            $respuesta[$producto_stock -> ps_id]['ps_calibre'] =    $producto_stock -> ps_calibre; 
            $respuesta[$producto_stock -> ps_id]['ps_cantidad'] =   $producto_stock -> ps_cantidad; 
            $respuesta[$producto_stock -> ps_id]['ps_compra_id'] =  $producto_stock -> ps_compra_id;
            $respuesta[$producto_stock -> ps_id]['ps_transf_id'] = $producto_stock -> ps_transf_id; 
            if($producto_stock -> ps_compra_id){    // Obtengo la cantidad que se ingresó por la compra
                $do_compra_detalle = DB_DataObject::factory('compra_detalle');  
                $do_compra_detalle -> whereAdd('detalle_compra_id ='.$producto_stock -> ps_compra_id.' AND detalle_ps_id = '.$producto_stock -> ps_id);
                $do_compra_detalle -> find(true);
            $respuesta[$producto_stock -> ps_id]['ps_cantidad_ingresada'] = $do_compra_detalle -> detalle_prod_cant;        

            }else if($producto_stock -> ps_transf_id){   // Obtengo la cantidad que se ingresó por la transf 
                $do_transf_detalle = DB_DataObject::factory('transferencias_detalle');
                $do_transf_detalle -> whereAdd('detalle_transferencia_id ='.$producto_stock -> ps_transf_id.' AND detalle_lote = '.$producto_stock -> ps_id);
                $do_transf_detalle -> find(true);
                $respuesta[$producto_stock -> ps_id]['ps_cantidad_ingresada'] = $do_transf_detalle -> detalle_producto_cantidad_destino;        

            }

            // $respuesta[$producto_stock -> ps_id]['ps_cantidad_ingresada'] =  $producto_stock -> ps_cantidad;     
            $respuesta[$producto_stock -> ps_id]['cantidad_vendida'] =  $producto_stock -> cantidad_vendida; 
            $respuesta[$producto_stock -> ps_id]['cantidad_devuelta'] = $producto_stock -> cantidad_devuelta; 
            $respuesta[$producto_stock -> ps_id]['cantidad_perdida'] =  $producto_stock -> cantidad_perdida; 
            if($producto_stock -> cantidad_transferida_destino){
                $respuesta[$producto_stock -> ps_id]['cantidad_transferida'] =  $producto_stock -> cantidad_transferida_destino; 
            }else{
                $respuesta[$producto_stock -> ps_id]['cantidad_transferida'] =  $producto_stock -> cantidad_transferida; 
            }
            $respuesta[$producto_stock -> ps_id]['stock_venta'] =   $producto_stock -> ps_cantidad; 
            $respuesta[$producto_stock -> ps_id]['stock_fisico'] =  $producto_stock -> cantidad_vendida_sin_retirar + $producto_stock -> ps_cantidad; 
            

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


        
        }

        return $respuesta;
    }

     function stockSinVenta($fi=false,$ff=false){
        if(!$ff){
            $ff = date('Y-m-d H:i:s');
        }

        //DB_DataObject::debugLevel(1);

        $producto = DB_DataObject::factory('producto');
        $categoria = DB_DataObject::factory('categoria');
        $tipo = DB_DataObject::factory('tipo');

        $producto_stock = DB_DataObject::factory('producto_stock');
        $compra = DB_DataObject::factory('compra');
        $proveedor = DB_DataObject::factory('proveedor');
        $transferencias = DB_DataObject::factory('transferencias');
        
        
        $compra -> joinAdd($proveedor,"LEFT");
        $categoria -> joinAdd($tipo);
        $producto -> joinAdd($categoria);
        $producto_stock -> joinAdd($producto);
        $producto_stock -> joinAdd($compra,"LEFT");
        $producto_stock -> joinAdd($transferencias,"LEFT");

        $fecha_hasta = new DateTime();  
        $f_desde =  $fecha_hasta -> modify("-1 day");
        

        // $venta = DB_DataObject::factory('venta');
        // $venta_detalle = DB_DataObject::factory('venta_detalle');
        
        // $venta_detalle -> joinAdd($venta,"LEFT");

        // $producto_stock -> joinAdd($venta_detalle,"LEFT");

        // $producto_stock -> whereAdd('venta_id IS NULL');

        $producto_stock -> find();

        while ($producto_stock -> fetch()) {
            $respuesta[$producto_stock -> ps_id]['fecha_compra'] = $producto_stock -> compra_fh;
            $respuesta[$producto_stock -> ps_id]['fecha_tranferencia'] = $producto_stock -> transf_fh;
            $respuesta[$producto_stock -> ps_id]['ps_lote'] =   $producto_stock -> ps_lote; 
            $respuesta[$producto_stock -> ps_id]['prov_nombre'] =   $producto_stock -> prov_nombre; 
            $respuesta[$producto_stock -> ps_id]['tipo_nombre'] =   $producto_stock -> tipo_nombre; 
            $respuesta[$producto_stock -> ps_id]['cat_nombre'] =    $producto_stock -> cat_nombre; 
            $respuesta[$producto_stock -> ps_id]['prod_alias'] =    $producto_stock -> prod_alias; 
            $respuesta[$producto_stock -> ps_id]['ps_calibre'] =    $producto_stock -> ps_calibre; 
            $respuesta[$producto_stock -> ps_id]['ps_cantidad'] =   $producto_stock -> ps_cantidad; 
            $respuesta[$producto_stock -> ps_id]['ps_compra_id'] =  $producto_stock -> ps_compra_id;
            $respuesta[$producto_stock -> ps_id]['ps_transf_id'] = $producto_stock -> ps_transf_id; 
            // $respuesta[$producto_stock -> ps_id]['ps_cantidad_ingresada'] =  $producto_stock -> ps_cantidad;     
            $respuesta[$producto_stock -> ps_id]['stock_fisico'] =   $producto_stock -> ps_cantidad; 
        }        

        // print_r(json_encode($respuesta));exit;
        return $respuesta;
    }

}
