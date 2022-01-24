<?php
/**
 * Table Definition for producto_stock
 */
require_once 'DB/DataObject.php';

class DataObjects_Producto_stock extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'producto_stock';      // table name
    public $ps_id;                           // int(11)  not_null primary_key auto_increment group_by
    public $ps_compra_id;                    // int(11)  group_by
    public $ps_producto_id;                  // int(11)  not_null group_by
    public $ps_costo_u;                      // float(11)  not_null group_by
    public $ps_cantidad;                     // int(11)  not_null group_by
    public $ps_transf_id;                    // int(11)  group_by
    public $ps_calibre;                      // varchar(256)  
    public $ps_precio_prom_venta;            // float(11)  not_null group_by
    public $ps_precio_prom_venta_fh;         // datetime(19)  
    public $ps_lote;                         // varchar(256)  
    public $ps_liquidado_fh;                 // datetime(19)  
    public $ps_precio_prom_venta_parcial;    // float(11)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Producto_stock',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function getCalibres($id){
        $do_producto_stock = DB_DataObject::factory('producto_stock');
        $do_producto_stock -> ps_producto_id = $id;
        $do_producto_stock -> find();
        $calibre = array();
        while ($do_producto_stock -> fetch()) {
            if($do_producto_stock -> ps_cantidad){
                $calibre[$do_producto_stock -> ps_calibre] = $calibre[$do_producto_stock -> ps_calibre] + $do_producto_stock -> ps_cantidad; 
            }
        }
       
        if($calibre){
            return $calibre;
        }else{
            return false;
        }
    }

    function getCalibresXLote($id){
        $do_producto_stock = DB_DataObject::factory('producto_stock');
        $do_producto_stock -> ps_producto_id = $id;
        $do_producto_stock -> find();
        $calibre = array();
        while ($do_producto_stock -> fetch()) {
            if($do_producto_stock -> ps_cantidad){
                $calibre[$do_producto_stock -> ps_calibre][$do_producto_stock -> ps_id] = $calibre[$do_producto_stock -> ps_calibre][$do_producto_stock -> ps_id] + $do_producto_stock -> ps_cantidad; 
            }
        }
       
        if($calibre){
            return $calibre;
        }else{
            return false;
        }
    }

    function getCalibresVentaFisico($id){
        $do_producto_stock = DB_DataObject::factory('producto_stock');
        $do_producto_stock -> ps_producto_id = $id;
        $do_producto_stock -> ps_cantidad > 0;
        $do_producto_stock -> find();

        $calibre = array();

        while ($do_producto_stock -> fetch()) {
            $calibre[$do_producto_stock -> ps_calibre]['venta'] += $do_producto_stock -> ps_cantidad;            
            $calibre[$do_producto_stock -> ps_calibre]['fisico'] += $do_producto_stock -> ps_cantidad;            
        }

        //Busco el stock vendido, pero que todavia no se despacho para sumarlo y llegar al stock fisico
        $do_ventas = DB_DataObject::factory('venta');
        $do_venta_detalle = DB_DataObject::factory('venta_detalle');
        $do_ventas -> joinAdd($do_venta_detalle);


        $do_ventas -> whereAdd('venta_estado_id IN (1,2) AND detalle_prod_id='.$id);
        $do_ventas -> find();
        $fisico= array();

        while($do_ventas -> fetch()){
            $calibre[$do_ventas -> detalle_prod_calibre]['fisico'] += $do_ventas -> detalle_prod_cant;
        }   

        //print_r($calibre);exit;
        
        return $calibre;
    }

    function getLotes($prod_id,$ps_calibre){
        //DB_DataObject::debugLevel(1);
        $do_producto_stock = DB_DataObject::factory('producto_stock');
        $do_producto_stock -> whereAdd('ps_producto_id ='.$prod_id.' AND ps_calibre = "'.$ps_calibre.'"');
    
        $do_producto_stock -> find();


        while($do_producto_stock -> fetch()){
        
            $do_ventas = DB_DataObject::factory('venta');       //Calculo el stock fisico
            $do_venta_detalle = DB_DataObject::factory('venta_detalle');
            $do_ventas -> joinAdd($do_venta_detalle);
            $do_ventas -> selectAdd();
            $do_ventas -> selectAdd('detalle_prod_cant');
            $do_ventas -> whereAdd('venta_estado_id IN (1,2) AND detalle_prod_lote='.$do_producto_stock -> ps_id);
            $do_ventas -> find();
            $fisico= $do_ventas -> count('detalle_prod_cant');

            $lotes[$do_producto_stock -> ps_id]['desc'] = $do_producto_stock -> ps_lote;
            $lotes[$do_producto_stock -> ps_id]['stock_venta'] = $do_producto_stock -> ps_cantidad;
            $lotes[$do_producto_stock -> ps_id]['stock_fisico'] = $do_producto_stock -> ps_cantidad + $fisico;
        }
        return $lotes;
    }


    function getCantidadxcalibreylote() {           //STOCK DE VENTA
        $do_producto_stock = DB_DataObject::factory('producto_stock');
        // $do_producto_stock -> ps_lote = $this -> ps_lote;
        $do_producto_stock -> ps_id = $this -> ps_id;
        $do_producto_stock -> find();
        $sum = 0;
        while ($do_producto_stock -> fetch()) {
            $sum += $do_producto_stock -> ps_cantidad;            
        }

        return $sum;
    }

    function getCantidadFisicaxcalibreylote() {             //STOCK FISICO
        $sum = $this -> ps_cantidad;            
  
        //Busco el stock vendido, pero que todavia no se despacho para sumarlo y llegar al stock fisico
        $do_ventas = DB_DataObject::factory('venta');
        $do_venta_detalle = DB_DataObject::factory('venta_detalle');
        $do_ventas -> joinAdd($do_venta_detalle);

        $do_ventas -> whereAdd('venta_estado_id IN (1,2) AND detalle_prod_lote='.$this -> ps_id);
        $do_ventas -> find();
        $fisico=0;

        while($do_ventas -> fetch()){
            $fisico += $do_ventas -> detalle_prod_cant;
        }
        
        $sum = $sum + $fisico;

        return $sum;
    }

    function getCostou($prod_id,$calibre) {
        $do_producto_stock = DB_DataObject::factory('producto_stock');
        $do_producto_stock -> ps_producto_id = $prod_id;
        $do_producto_stock -> ps_calibre = $calibre;
        $do_producto_stock -> orderBy('ps_id DESC');
        $do_producto_stock -> find(true);


        return $do_producto_stock -> ps_costo_u;
    }
    function getCostouXLote($lote) {
        $do_producto_stock = DB_DataObject::factory('producto_stock');
        $do_producto_stock -> ps_id = $lote;
        $do_producto_stock -> find(true);

        return $do_producto_stock -> ps_costo_u;
    }


    function getNombrePuesto() {
        $puesto = DB_DataObject::factory('puesto');
        $puesto -> puesto_id = $this -> transf_origen;
        $puesto -> find(true);

        return $puesto -> puesto_nombre;
    }
    function getNombreProv() {
        $prov = DB_DataObject::factory('proveedor');
        $prov -> prov_id = $this -> compra_prov_id;
        $prov -> find(true);

        return $prov -> prov_nombre;
    }

    function getListado($id) {

        $transf = DB_DataObject::factory('transferencias');
        $compra = DB_DataObject::factory('compra');

        $this -> joinAdd($transf,'LEFT');
        $this -> joinAdd($compra,'LEFT');

        $this -> ps_producto_id = $id;
        $this -> find();

        while ($this -> fetch()) {
            $resp[$this -> ps_id]['id'] = $this -> ps_id;
            $resp[$this -> ps_id]['calibre'] = $this -> ps_calibre;
            $resp[$this -> ps_id]['cantidad'] = $this -> ps_cantidad;
            if($this -> ps_compra_id) {
                $resp[$this -> ps_id]['origen'] = 'Compra C00'.$this -> ps_compra_id;
                $resp[$this -> ps_id]['prov'] = 'Prov <b>'.$this -> getNombreProv().'</b>';
            }
            if($this -> ps_transf_id) {
                $resp[$this -> ps_id]['origen'] = 'Transferencia TR00'.$this -> ps_transf_id;
                $resp[$this -> ps_id]['prov'] = 'Puesto <b>'.$this -> getNombrePuesto().'</b>';
            }
            if($this -> ps_precio_prom_venta > 0) {
                $resp[$this -> ps_id]['ppv'] = '$ '.$this -> ps_precio_prom_venta;
            } else {
                $resp[$this -> ps_id]['ppv'] = '-';
            }
        }

        return $resp;

    }

    function getDetalleLote($id) {
        $this -> ps_id = $id;

        $transf = DB_DataObject::factory('transferencias');
        $compra = DB_DataObject::factory('compra');
        


        $this -> joinAdd($transf,'LEFT');
        $this -> joinAdd($compra,'LEFT');

        $this -> find(true);

        $producto = DB_DataObject::factory('producto');
        $productos = $producto -> getProductos($this -> ps_producto_id);

        $descarga = DB_DataObject::factory('descarga');
        $costo_unitario_descarga = $descarga -> getCostoDescarga($this -> ps_id);

        $carga = DB_DataObject::factory('carga');
        $costo_unitario_carga = $carga -> getCostoCarga($this -> ps_id);


        $resp['lote']['id'] = $this -> ps_id;
        $resp['lote']['calibre'] = $this -> ps_calibre;
        $resp['lote']['cantidad'] = $this -> ps_cantidad;
        $costo_u_origen = $this -> ps_costo_u - $costo_unitario_descarga - $costo_unitario_carga;
        $resp['lote']['costo_u'] = '$ '.$costo_u_origen;
        $resp['lote']['costo_u_final'] = '$ '.$this -> ps_costo_u;
        $resp['lote']['desc'] = $this -> ps_lote;
        $resp['lote']['costo_u_descarga'] = '$ '.$costo_unitario_descarga;
        $resp['lote']['costo_u_carga'] = '$ '.$costo_unitario_carga;
        if($this -> ps_liquidado_fh == '' and $this -> ps_costo_u == 0 ){
            $resp['lote']['liquidado'] = "<span style='color:yellow;'>Sin liquidar</span>";
        }else{
            $resp['lote']['liquidado'] = "<span style='color:Green;'>Liquidado</span>";
        }

        
        if($this -> ps_compra_id) {
            $resp['lote']['origen'] = 'Compra C00'.$this -> ps_compra_id;
            $resp['lote']['fecha'] = date('d/m/Y H:i',strtotime($this -> compra_fh));
            $resp['lote']['prov'] = 'Prov '.$this -> getNombreProv().'';
        }
        if($this -> ps_transf_id) {
            $resp['lote']['origen'] = 'Transferencia TR00'.$this -> ps_transf_id;
            $resp['lote']['fecha'] = date('d/m/Y H:i',strtotime($this -> transf_fh));
            $resp['lote']['prov'] = 'Puesto '.$this -> getNombrePuesto().'';
        }
        if($this -> ps_precio_prom_venta > 0) {
            $resp['lote']['ppv'] = '$ '.$this -> ps_precio_prom_venta;
        } else {
            $resp['lote']['ppv'] = '-';
        }

        $resp['producto'] = $productos;

        return $resp;

    }

    function getMovimientosLote($id) {
        // +   Compra/transferencia de ingreso
        // -   Ventas
        // -   Transferencias
        // -   Devolucion (Quitar de stock)
        // +   Devolucion (Restaurar stock)

        $this -> ps_id = $id;
        $this -> find(true);

        // Compras
        if($this -> ps_compra_id) {
            $compra_original = DB_DataObject::factory('compra');
            $compra_original -> compra_id = $this -> ps_compra_id;
            $compra_original -> find(true);

            $compra_original_detalle = DB_DataObject::factory('compra_detalle');
            $compra_original_detalle -> detalle_compra_id = $this -> ps_compra_id;
            $compra_original_detalle -> detalle_prod_id = $this -> ps_producto_id;
            $compra_original_detalle -> detalle_prod_calibre = $this -> ps_calibre;
            $compra_original_detalle -> find(true);

            $resp[$compra_original -> compra_fh]['op'] = 'Compra C00'. $compra_original -> compra_id;
            $resp[$compra_original -> compra_fh]['cant'] = $compra_original_detalle -> detalle_prod_cant;
            $resp[$compra_original -> compra_fh]['tipo'] = '+';

            // Devolucion Compras
                
            $devolucion_mercaderia = DB_DataObject::factory('devolucion_mercaderia');
            $devolucion_mercaderia -> dev_compra_detalle_id = $compra_original_detalle -> detalle_id;
            $devolucion_mercaderia -> dev_rest_stock = 1;
            $devolucion_mercaderia -> find();
            while ($devolucion_mercaderia -> fetch()) {
                $resp[$devolucion_mercaderia -> dev_fh]['op'] = 'Devolución D00'. $devolucion_mercaderia -> dev_id;
                $resp[$devolucion_mercaderia -> dev_fh]['cant'] = $devolucion_mercaderia -> dev_cantidad;
                $resp[$devolucion_mercaderia -> dev_fh]['tipo'] = '-';
            }
        // Transferencias
        } elseif($this -> ps_transf_id){
            $transf_original = DB_DataObject::factory('transferencias');
            $transf_original -> transf_id = $this -> ps_transf_id;
            $transf_original -> find(true);

            $transf_original_detalle = DB_DataObject::factory('transferencias_detalle');
            $transf_original_detalle -> detalle_transferencia_id = $this -> ps_transf_id;
            $transf_original_detalle -> detalle_producto_id = $this -> ps_producto_id;
            $transf_original_detalle -> detalle_calibre = $this -> ps_calibre;
            $transf_original_detalle -> find(true);

            $resp[$transf_original -> transf_fh]['op'] = 'Transferencia T00'. $transf_original -> transf_id;
            $resp[$transf_original -> transf_fh]['cant'] = $transf_original_detalle -> detalle_producto_cantidad_destino;
            $resp[$transf_original -> transf_fh]['tipo'] = '+';
        }

        // Ventas
            $venta = DB_DataObject::factory('venta');
            $venta_detalle = DB_DataObject::factory('venta_detalle');
            $venta_detalle -> joinAdd($venta);

            // $venta_detalle_stock = DB_DataObject::factory('venta_detalle_stock');
            // $venta_detalle_stock -> joinAdd($venta_detalle);
            $venta_detalle -> whereAdd('venta_estado_id != 3 AND detalle_prod_lote = '.$this -> ps_id);
            $venta_detalle -> find();
            while ($venta_detalle -> fetch()) {
                $resp[$venta_detalle -> venta_fh]['op'] = 'Venta V00'. $venta_detalle -> venta_id;
                $resp[$venta_detalle -> venta_fh]['cant'] = $venta_detalle -> detalle_prod_cant - $venta_detalle -> detalle_cant_dev;
                $resp[$venta_detalle -> venta_fh]['tipo'] = '-';
                $ppv_aux = (($venta_detalle -> detalle_prod_precio_u *  $resp[$venta_detalle -> venta_fh]['cant']) - $venta_detalle -> detalle_descuento_parcial) / $venta_detalle -> detalle_prod_cant;
                $resp[$venta_detalle -> venta_fh]['ppv'] = '$ '.$ppv_aux;
                $resp[$venta_detalle -> venta_fh]['pv'] = '$ '.$venta_detalle -> detalle_prod_precio_u;
                $resp[$venta_detalle -> venta_fh]['desc'] = '$ '.$venta_detalle -> detalle_descuento_parcial;

                // Devolucion ventas

                if($venta_detalle -> detalle_cant_dev > 0) {
                    $devolucion_mercaderia = DB_DataObject::factory('devolucion_mercaderia');
                    $devolucion_mercaderia -> dev_venta_detalle_id = $venta_detalle -> detalle_id;
                    $devolucion_mercaderia -> dev_rest_stock = 1;
                    $devolucion_mercaderia -> find();
                    while ($devolucion_mercaderia -> fetch()) {
                        $resp[$devolucion_mercaderia -> dev_fh]['op'] = 'Devolución D00'. $devolucion_mercaderia -> dev_id;
                        $resp[$devolucion_mercaderia -> dev_fh]['cant'] = $devolucion_mercaderia -> dev_cantidad;
                        $resp[$devolucion_mercaderia -> dev_fh]['tipo'] = '+';
                    }
                }
            }

        // Transferencias
            $transferencia = DB_DataObject::factory('transferencias');
            $transferencia_detalle = DB_DataObject::factory('transferencias_detalle');
            $transferencia_detalle -> joinAdd($transferencia);

            $transferencia_detalle_stock = DB_DataObject::factory('transferencias_detalle_stock');
            $transferencia_detalle_stock -> joinAdd($transferencia_detalle);
            $transferencia_detalle_stock -> tds_prodstock_id = $this -> ps_id;

            $transferencia_detalle_stock -> find();

            while ($transferencia_detalle_stock -> fetch()) {
                $resp[$transferencia_detalle_stock -> transf_fh]['op'] = 'Transferencia T00'. $transferencia_detalle_stock -> transf_id;
                $resp[$transferencia_detalle_stock -> transf_fh]['cant'] = $transferencia_detalle_stock -> detalle_producto_cantidad_destino;
                $resp[$transferencia_detalle_stock -> transf_fh]['tipo'] = '-';
                $resp[$transferencia_detalle_stock -> transf_fh]['ppv'] = '$ '.$transferencia_detalle_stock -> detalle_ppv;
            }

        // Devoluciones sin Restaurar Stock -> Perdidas de mercaderia en transferencias
            $perdida_mercaderia = DB_DataObject::factory('perdida_mercaderia');
            $perdida_mercaderia -> perdida_ps_id = $this -> ps_id;
            $perdida_mercaderia -> find();

            while($perdida_mercaderia -> fetch()){
                $resp[$perdida_mercaderia -> perdida_fh]['op'] = 'Perdida P00'. $perdida_mercaderia -> perdida_id;
                $resp[$perdida_mercaderia -> perdida_fh]['cant'] = $perdida_mercaderia -> perdida_cantidad;
                $resp[$perdida_mercaderia -> perdida_fh]['tipo'] = '-';
                $resp[$perdida_mercaderia -> perdida_fh]['ppv'] = '$ 0';
            }


        ksort($resp);

        return $resp;

    }


    function getPPVenta(){
        $venta = DB_DataObject::factory('venta');
        $venta_detalle = DB_DataObject::factory('venta_detalle');
        
        $venta_detalle -> joinAdd($venta);
        
        $venta_detalle -> whereAdd('venta_estado_id IN (2,4) AND detalle_prod_lote = '.$this -> ps_id); //La venta en estado saldada/despachada
        $venta_detalle -> find();
        //PRECIO PROMEDIO DE VENTAS
        $i = 0;
        while ($venta_detalle -> fetch()) {
            $devolucion_mercaderia = DB_DataObject::factory('devolucion_mercaderia');
            $devolucion_mercaderia -> dev_venta_detalle_id = $venta_detalle -> detalle_id;
            $devolucion_mercaderia -> find();

            while ($devolucion_mercaderia -> fetch()) {
                if($devolucion_mercaderia -> dev_rest_stock == 1){
                       $cantidad_devuelta = $devolucion_mercaderia -> dev_cantidad;
                }
                
            }

            //HAY QUE VERIFICAR SI LA CANTIDAD DEVUELTA SE RESTAURÓ STOCK O NO, XQ SINO DA MAL
            $objeto['detalle_venta'][$venta_detalle -> detalle_id]['cantidad'] = $venta_detalle -> detalle_prod_cant - $venta_detalle -> detalle_cant_dev;
            $objeto['detalle_venta'][$venta_detalle -> detalle_id]['precio_u'] = $venta_detalle -> detalle_prod_precio_u;

            $total_stock_v +=  $venta_detalle -> detalle_prod_cant - $cantidad_devuelta;  
            $total_descuento += $venta_detalle -> detalle_descuento_parcial;  
            $promedio_v +=  $objeto['detalle_venta'][$venta_detalle -> detalle_id]['cantidad'] * $objeto['detalle_venta'][$venta_detalle -> detalle_id]['precio_u'];
        }

        $descuento_unitario = $total_descuento / $total_stock_v;
        $objeto[$venta_detalle -> detalle_prod_lote]['detalle_venta']['total_stock'] = $total_stock_v;
        $objeto[$venta_detalle -> detalle_prod_lote]['detalle_venta']['ppv'] = ($promedio_v / $total_stock_v) - $descuento_unitario;
        $objeto[$venta_detalle -> detalle_prod_lote]['detalle_venta']['descuento_unitario'] = $descuento_unitario;

        return $objeto;
    }
    function getPPTransf(){
            
        //PRECIO PROMEDIO EN TRANSFERENCIAS
        //$transferencia_detalle_stock = DB_DataObject::factory('transferencias_detalle_stock');

        //$transferencia_detalle_stock -> joinAdd($transferencia_detalle);
	   //DB_DataObject::debugLevel(1);
        $transferencias = DB_DataObject::factory('transferencias');
	    $transferencia_detalle = DB_DataObject::factory('transferencias_detalle');
        $producto_stock = DB_DataObject::factory('producto_stock');

        $transferencia_detalle -> joinAdd($transferencias);
        $transferencia_detalle -> joinAdd($producto_stock);
        $transferencia_detalle -> whereAdd('transf_tipo = 1 AND transf_estado IN (4,5) AND detalle_lote = '.$this -> ps_id);        //TRANSF aceptada con o sin dif de stock
        
            $transferencias_clone = DB_DataObject::factory('transferencias');
            $transferencia_detalle_clone = DB_DataObject::factory('transferencias_detalle');
            $producto_stock_clone = DB_DataObject::factory('producto_stock');

            $transferencia_detalle_clone -> joinAdd($transferencias_clone);
            $transferencia_detalle_clone -> joinAdd($producto_stock_clone);
            $transferencia_detalle_clone -> whereAdd('transf_tipo = 1 AND detalle_ppv = 0 AND detalle_lote ='.$this -> ps_id);       //Si todavia no tiene un algun PPV cargado no informo PPV
            $transferencia_detalle_clone -> find(true);
            
            if($transferencia_detalle_clone -> N > 0){
                $objeto['estado'] = 500;
                return $objeto;
            }

        $transferencia_detalle -> find();
       
        $i=0;
        while ($transferencia_detalle -> fetch()) {

            $objeto[$transferencia_detalle -> ps_id]['detalle_transferencia'][$transferencia_detalle -> detalle_id]['cantidad'] = $transferencia_detalle -> detalle_producto_cantidad_destino;
            $objeto[$transferencia_detalle -> ps_id]['detalle_transferencia'][$transferencia_detalle -> detalle_id]['precio_u'] = $transferencia_detalle -> detalle_ppv;
            $total_stock_t += $transferencia_detalle -> detalle_producto_cantidad_destino; 
            $promedio_t += $transferencia_detalle -> detalle_producto_cantidad_destino * $transferencia_detalle -> detalle_ppv;
            $diferencia_stock +=  $transferencia_detalle -> detalle_producto_cantidad_origen - $transferencia_detalle -> detalle_producto_cantidad_destino;
        }

        $objeto[$transferencia_detalle -> ps_id]['detalle_transferencia']['total_stock'] = $total_stock_t;
        $objeto[$transferencia_detalle -> ps_id]['detalle_transferencia']['ppv'] = $promedio_t / $total_stock_t;
        $objeto[$transferencia_detalle -> ps_id]['detalle_transferencia']['diferencia_stock'] = $diferencia_stock;

        return $objeto;
        }


    function getPPVFinal(){
        //ACTUALIZO LOS PPV DE PRODUCTO STOCK
        $this -> whereAdd('ps_cantidad = 0 and ps_precio_prom_venta_parcial = 0'); //Sin stock y sin ppv parcial cargado
      
        $this -> find();

        while($this -> fetch()){
            $venta = DB_DataObject::factory('venta');
            $venta_detalle = DB_DataObject::factory('venta_detalle');
             $venta_detalle -> joinAdd($venta);

            $venta_detalle -> whereAdd('detalle_prod_lote = '.$this -> ps_id.' AND venta_estado_id = 1');
            $venta_detalle -> find(true);
            
            if(!$venta_detalle -> N){        //Si existe una venta pendiente de ese lote no puedo calcular ppv.
                $ppventa = $this -> getPPVenta();
                $pptransf = $this -> getPPTransf();
             
                // print_r($ppventa);
                // print_r("<br>");
                // print_r($pptransf);
                // exit;
                if($pptransf['estado'] != 500){
                    $objeto[$this -> ps_id]['total_stock'] = $ppventa[$this -> ps_id]['detalle_venta']['total_stock'] + $pptransf[$this -> ps_id]['detalle_transferencia']['total_stock'];
                    $objeto[$this -> ps_id]['ppv'] = ($ppventa[$this -> ps_id]['detalle_venta']['ppv'] * $ppventa[$this -> ps_id]['detalle_venta']['total_stock'] + $pptransf[$this -> ps_id]['detalle_transferencia']['ppv'] * $pptransf[$this -> ps_id]['detalle_transferencia']['total_stock']) /  $objeto[$this -> ps_id]['total_stock'];
                    
                    $this -> ps_precio_prom_venta_parcial = $objeto[$this -> ps_id]['ppv'];
                    $this -> update();

                    if($this -> ps_transf_id){
                        $do_transferencia_detalle= DB_DataObject::factory('transferencias_detalle');
                        ///OJOOO, verificar si esta bien que filtre por ps_lote y no por ps_id
                        $do_transferencia_detalle-> whereAdd('detalle_transferencia_id = '.$this -> ps_transf_id.' AND detalle_lote_desc = "'.$this -> ps_lote.'"');
                        $do_transferencia_detalle -> find(true);
                        
                        $do_transferencia_detalle -> detalle_ppv = $this -> ps_precio_prom_venta_parcial;
                        $do_transferencia_detalle -> detalle_liquidado_fh = date('Y-m-d H:i:s');
                        $do_transferencia_detalle -> update();
                    }
                }else{
                    //Error en calculo ppv transf o venta
                }
            }


        }   
    }

    function getPPVLiquidado(){
        $this -> whereAdd('ps_cantidad = 0 and ps_precio_prom_venta_parcial != 0 AND ps_precio_prom_venta = 0'); //Sin stock, con ppv parcial y sin ppv cargado
      
        $this -> find();

        while($this -> fetch()){
            $venta = DB_DataObject::factory('venta');
            $venta_detalle = DB_DataObject::factory('venta_detalle');
             $venta_detalle -> joinAdd($venta);

            $venta_detalle -> whereAdd('detalle_prod_lote = '.$this -> ps_id.' AND venta_estado_id = 1');
            $venta_detalle -> find(true);
            
            if(!$venta_detalle -> N){        //Si existe una venta pendiente de ese lote no puedo calcular ppv.
                $ppventa = $this -> getPPVenta();
                $pptransf = $this -> getPPTransf();
             
                if($pptransf['estado'] != 500){
                    $objeto[$this -> ps_id]['total_stock'] = $ppventa[$this -> ps_id]['detalle_venta']['total_stock'] + $pptransf[$this -> ps_id]['detalle_transferencia']['total_stock'];
                    $objeto[$this -> ps_id]['ppv'] = ($ppventa[$this -> ps_id]['detalle_venta']['ppv'] * $ppventa[$this -> ps_id]['detalle_venta']['total_stock'] + $pptransf[$this -> ps_id]['detalle_transferencia']['ppv'] * $pptransf[$this -> ps_id]['detalle_transferencia']['total_stock']) /  $objeto[$this -> ps_id]['total_stock'];
                    
                    $this -> ps_precio_prom_venta = $objeto[$this -> ps_id]['ppv'];
                    $this -> update();
                }else{
                    //Error en calculo ppv transf o venta
                }
            }


        }   
    }
    function getLotesConciliacion($fecha_inicio){

        $producto = DB_DataObject::factory('producto');
        $categoria = DB_DataObject::factory('categoria');
        $tipo = DB_DataObject::factory('tipo');

        $categoria -> joinAdd($tipo);
        $producto -> joinAdd($categoria);
        $this -> joinAdd($producto);

        $this -> whereAdd('ps_precio_prom_venta_fh > "'.$fecha_inicio.'" OR ps_cantidad > 0');
        $this -> find();

        while($this ->  fetch()) {
            $r[$this -> ps_id]['id'] = $this -> ps_id;
            $r[$this -> ps_id]['producto'] = $this -> tipo_nombre .' - '. $this -> cat_nombre .' - '.$this -> prod_nombre .' C. '. $this -> ps_calibre;
            $r[$this -> ps_id]['prod'] = $this -> ps_producto_id;
            $r[$this -> ps_id]['cal'] = $this -> ps_calibre;
            $r[$this -> ps_id]['cant'] = $this -> getCantidadFisicaxcalibreylote();
            $r[$this -> ps_id]['cant_venta'] = $this -> ps_cantidad;
        }

        return $r;

    }


    function liquidarMercaderia($id){
        //RECAUDACION BRUTA
        //COSTO CARGA
        //COSTO DESCARGA
        //GASTOS
        //COMISION
        //FLETE
        //CANTIDAD TOTAL DE LA COMPRA/TRANSFERENCIA
        // COSTO U = (REC.BR / CANT) - COMISION - FLETE - CARGA - DESCARGA - GASTOS

        $venta = DB_DataObject::factory('venta');
        $venta_detalle = DB_DataObject::factory('venta_detalle');
        $venta_detalle_stock = DB_DataObject::factory('venta_detalle_stock');
        
        $venta_detalle -> joinAdd($venta);
        $producto_stock = DB_DataObject::factory('producto_stock');
        
        $venta_detalle_stock -> joinAdd($venta_detalle);
        $venta_detalle_stock -> joinAdd($producto_stock);
        
        $venta_detalle_stock -> whereAdd('venta_estado_id IN (2,4) AND ps_id = '.$id); //Venta en estado saldada/despachada
        $venta_detalle_stock -> find();

        $descarga = DB_DataObject::factory('descarga');
        $costo_unitario_descarga = $descarga -> getCostoDescarga($this -> ps_id);

        $carga = DB_DataObject::factory('carga');
        $costo_unitario_carga = $carga -> getCostoCarga($this -> ps_id);

        $respuesta = array();
        while($venta_detalle_stock -> fetch()){
            $respuesta['rec_bruta'] += ($venta_detalle_stock -> detalle_prod_precio_u * $venta_detalle_stock -> detalle_prod_cant) - $venta_detalle_stock -> detalle_descuento_parcial;
            $respuesta['total_prod'] += $venta_detalle_stock -> detalle_prod_cant;
            $respuesta['total_desc'] += $venta_detalle_stock -> detalle_descuento_parcial;

        }
        $comision = 0;
        $flete = 0;
        $gastos = 0;
        $precio_costo_final = ($respuesta['rec_bruta'] / $respuesta['total_prod']) - $comision - $flete - $costo_unitario_carga - $costo_unitario_descarga - $gastos;

        $producto_stock_update = DB_DataObject::factory('producto_stock');
        $producto_stock_update -> ps_id = $id;
        $producto_stock_update -> find(true);

        $producto_stock_update -> ps_costo_u = $precio_costo_final;
        $producto_stock_update -> ps_liquidado_fh = date('Y-m-d H:i:s');
        $id = $producto_stock_update -> update();

        if($id){
            return true;
        }else{
            return false;
        }

    }

    function getNombrePs($id) {
        $producto = DB_DataObject::factory('producto');
        $categoria = DB_DataObject::factory('categoria');
        $tipo = DB_DataObject::factory('tipo');

        $categoria -> joinAdd($tipo);
        $producto -> joinAdd($categoria);
        $this -> joinAdd($producto);
        $this -> ps_id = $id;
        $this -> find(true);

        return ''.$this -> tipo_nombre .' / '.$this -> cat_nombre .' / '.$this -> prod_nombre .' / '.$this -> ps_calibre.'';
    }
     
}
