<?php
/**
 * Table Definition for devolucion_mercaderia
 */
require_once 'DB/DataObject.php';

class DataObjects_Devolucion_mercaderia extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'devolucion_mercaderia';    // table name
    public $dev_id;                          // int(11)  not_null primary_key auto_increment group_by
    public $dev_prod_id;                     // int(11)  not_null group_by
    public $dev_fh;                          // datetime(19)  not_null
    public $dev_monto;                       // float(7)  not_null group_by
    public $dev_cantidad;                    // int(11)  not_null group_by
    public $dev_cliente_id;                  // int(11)  group_by
    public $dev_prov_id;                     // int(11)  group_by
    public $dev_obs;                         // varchar(254)  
    public $dev_rest_stock;                  // int(1)  group_by
    public $dev_venta_id;                    // int(11)  group_by
    public $dev_venta_detalle_id;            // int(11)  group_by
    public $dev_compra_id;                   // int(11)  group_by
    public $dev_compra_detalle_id;           // int(11)  group_by
    public $dev_boleto_id;                   // int(11)  group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Devolucion_mercaderia',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE


    function devolverMercaderiaVenta($objeto){
        $this -> dev_prod_id = $objeto['prod'][0]['id'];
        $this -> dev_fh = date('Y-m-d H:i:s');
        $this -> dev_monto = $objeto['input_monto'];
        $this -> dev_cantidad = $objeto['input_cantidad_dev'];
        $this -> dev_obs = $objeto['input_obs_devolver'];
        $this -> dev_rest_stock = $objeto['input_restaurar_stock'];

        if($objeto['input_id_cliente']){
            $this -> dev_cliente_id = $objeto['input_id_cliente'];
            $this -> dev_venta_id = $objeto['concepto_venta_id'];
            $this -> dev_venta_detalle_id = $objeto['concepto_venta_detalle_id'];
            $id_dm = $this -> insert();

            $venta_detalle = DB_DataObject::factory('venta_detalle');
            $venta_detalle -> detalle_id = $objeto['concepto_venta_detalle_id'];
            $venta_detalle -> find(true);
            $venta_detalle -> detalle_cant_dev = $venta_detalle -> detalle_cant_dev + $objeto['input_cantidad_dev'];
            $venta_detalle -> update();

            
            if($objeto['tieneBoleto'] == "DESC"){     //Se genera un descuento
                
                $upd = DB_DataObject::factory('devolucion_mercaderia');
                $upd -> dev_id = $id_dm;
                $upd -> find(true);
                $upd -> dev_boleto_id = $objeto['boleto_id'];
                $upd -> update();

                $do_boleto = DB_DataObject::factory('boleto');
                $do_boleto -> boleto_id = $objeto['boleto_id'];
                $do_boleto -> find(true);

                $do_boleto -> boleto_descuento = $do_boleto -> boleto_descuento + $objeto['input_monto'];
                $do_boleto -> boleto_descuento = date('Y-m-d H:i:s');
                $do_boleto -> update();

                $do_boleto_descuento = DB_DataObject::factory('boleto_descuento');
                $do_boleto_descuento -> bol_desc_bol_id = $objeto['boleto_id'];
                $do_boleto_descuento -> bol_desc_monto = $objeto['input_monto'];
                $do_boleto_descuento -> bol_desc_fh =  date('Y-m-d H:i:s');
                $do_boleto_descuento -> bol_desc_tipo = 1;      //Descuento por devolucion de mercaderia
                $id_desc = $do_boleto_descuento -> insert();
                
                $do_cliente_cuenta_corriente = DB_DataObject::factory('cliente_cuenta_corriente');
                $do_cliente_cuenta_corriente -> cargarDescuento($objeto['input_id_cliente'],$id_desc,$objeto['input_monto']);
            } else {     //Se genera una nota de credito

                if ($objeto["devoluciondinero"]){ // si se le devolvio el dinero en efectivo, genero una nota de credito, una de debito y una salida de caja

                    //NOTA DE CREDITO POR DEV DE MERCADERIA
                    $do_nota_3 = DB_DataObject::factory('notas');
                    $param['combo_tipo'] = "NC";    //Genero una nota de credito al cliente por el monto de la devolucion
                    $param['nota_tipo'] = "NC";    
                    $param['input_id_cliente'] = $objeto['input_id_cliente'];
                    $param['input_monto'] = $objeto['input_monto'];
                    $param['nota_fh'] = date('Y-m-d H:i:s');
                    $param['id_dm'] = $id_dm;

                    $param['input_obs_nota'] = "Devolucion de mercaderia en venta: ".$objeto['concepto_venta_id'];

                    $ND = $do_nota_3 -> nuevaNotaDesdeConcepto($param);


                    //NOTA DE DEBITO POR DEV DE DINERO
                    $do_nota2 = DB_DataObject::factory('notas');
                    $param2['combo_tipo'] = "ND";    //Genero una nota de debito al cliente por el monto de la devolucion
                    $param2['nota_tipo'] = "ND";    
                    $param2['input_id_cliente'] = $objeto['input_id_cliente'];
                    $param2['input_monto'] = $objeto['input_monto'];
                    $param2['nota_fh'] = date('Y-m-d H:i:s');
                    $param2['id_dm'] = $id_dm;
                    $param2['input_obs_nota'] = "Entrega de efectivo por devolucion de mercaderia en venta: ".$objeto['concepto_venta_id'];
                    $NDeb = $do_nota2 -> nuevaNotaDesdeConcepto($param2);

                   
                    // SALIDA DE CAJA POR ENTREGA DE DINERO
                    $gasto = DB_DataObject::factory('gasto');

                    $gasto -> gasto_fh = date('Y-m-d H:i:s');
                    $gasto -> gasto_monto_total = $objeto['input_monto'];
                    $gasto -> gasto_usuario_id = $_SESSION['usuario']['id'];
                    $gasto -> gasto_concepto = 'Devolucion de mercaderia: V00'.$objeto['concepto_venta_id'];;
                    $gasto -> gasto_categoria = 10;
                    $gasto -> gasto_observacion = 'Entrega de dinero por devolucion de mercaderia';

                    $id_gasto = $gasto -> insert();

                }else{
                    $do_nota = DB_DataObject::factory('notas');
                    $param['combo_tipo'] = "NC";    //Genero una nota de credito al cliente por el monto de la devolucion
                    $param['nota_tipo'] = "NC";    
                    $param['input_id_cliente'] = $objeto['input_id_cliente'];
                    $param['input_monto'] = $objeto['input_monto'];
                    $param['nota_fh'] = date('Y-m-d H:i:s');
                    $param['id_dm'] = $id_dm;

                    $param['input_obs_nota'] = "Devolucion de mercaderia en venta: ".$objeto['concepto_venta_id'];

                    $ND = $do_nota -> nuevaNotaDesdeConcepto($param);
                }
            }

            // Cargo los prodocutos devueltos
            
            $do_venta_detalle_stock = DB_DataObject::factory('venta_detalle_stock');
            $do_venta_detalle_stock -> vds_venta_detalle_id = $objeto['concepto_venta_detalle_id'];
            $do_venta_detalle_stock -> find(true);
            $id_vds = $do_venta_detalle_stock -> vds_prodstock_id;

            $do_venta_detalle_stock -> vds_cant_dev = $do_venta_detalle_stock -> vds_cant_dev + $objeto['input_cantidad_dev'];
            $do_venta_detalle_stock -> update();

            //Restauro Stock

            if($objeto['input_restaurar_stock']){
                $do_producto_stock = DB_DataObject::factory('producto_stock');
                $do_producto_stock -> ps_id = $id_vds;
                $do_producto_stock -> find(true);

                $do_producto_stock -> ps_precio_prom_venta = 0; //como hubo una dev de mercaderia, pongo en 0 el ppv, para que se realice el calculo de nuevo
                $do_producto_stock -> ps_cantidad = $do_producto_stock -> ps_cantidad + $objeto['input_cantidad_dev'];
                $do_producto_stock -> ps_precio_prom_venta_parcial = 0;

                $do_producto_stock -> update();
            }else{
                //Perdida de mercaderia
                $do_perdida_mercaderia = DB_DataObject::factory('perdida_mercaderia');
                $do_perdida_mercaderia -> perdida_ps_id = $id_vds;
                $do_perdida_mercaderia -> perdida_desc = "Devolucion de mercaderia en venta ".$objeto['concepto_venta_id'];
                $do_perdida_mercaderia -> perdida_cantidad = $objeto['input_cantidad_dev'];
                $do_perdida_mercaderia -> perdida_fh = date('Y-m-d H:i:s');
                $do_perdida_mercaderia -> perdida_usua_id = $_SESSION['usuario']['id'];
                $do_perdida_mercaderia -> perdida_tipo_op = 1;
                $do_perdida_mercaderia -> perdida_op_id = $objeto['concepto_venta_id'];
                $do_perdida_mercaderia -> perdida_prod_nombre = $objeto['input_obs_devolver'];
                $do_perdida_mercaderia -> insert();


                //Pongo en 0 el ppv parcial para recalcularlo
                $do_producto_stock = DB_DataObject::factory('producto_stock');
                $do_producto_stock -> ps_id = $id_vds;
                $do_producto_stock -> find(true);

                $do_producto_stock -> ps_precio_prom_venta = 0; //como hubo una dev de mercaderia, pongo en 0 el ppv, para que se realice el calculo de nuevo
                $do_producto_stock -> ps_precio_prom_venta_parcial = 0;

                $do_producto_stock -> update();
            }

        }
        return $id_dm;
    }

    function devolverMercaderiaCompra($objeto){
         $this -> dev_prod_id = $objeto['combo_prod_dev'];
         $this -> dev_fh = date('Y-m-d H:i:s');
         $this -> dev_monto = $objeto['input_monto'];
         $this -> dev_cantidad = $objeto['input_cantidad_dev'];
         $this -> dev_obs = $objeto['input_obs_devolver'];
         $this -> dev_rest_stock = $objeto['input_restaurar_stock'];
  
         if($objeto['compra_prov_id']){
             $this -> dev_prov_id = $objeto['compra_prov_id'];
             $this -> dev_compra_id = $objeto['concepto_compra_id'];
             $this -> dev_compra_detalle_id = $objeto['concepto_compra_detalle_id'];
             $param['combo_tipo'] = 6;    //Genero una nota de debito al proveedor por el monto de la devolucion
             $param['nota_tipo'] = "ND";    //Genero una nota de debito al proveedor por el monto de la devolucion
             $param['input_id_prov'] = $objeto['compra_prov_id'];
             $param['input_obs_concepto'] = "Devolucion de mercaderia en compra: ".$objeto['concepto_compra_id'];
             $param['concepto_fh'] = date('Y-m-d H:i:s');
 
             //Sumo la cantidad de mercaderia devuelta, para que la proxima no exceda el total vendido
             $do_compra_detalle = DB_DataObject::factory('compra_detalle');
             $do_compra_detalle -> detalle_id = $objeto['concepto_compra_detalle_id'];
             $do_compra_detalle -> find(true);
 
             $do_compra_detalle -> detalle_prod_dev = $do_compra_detalle -> detalle_prod_dev + $objeto['input_cantidad_dev'];
             $do_compra_detalle -> update();

             $do_producto_stock = DB_DataObject::factory('producto_stock');
             $do_producto_stock -> whereAdd('ps_id = '.$do_compra_detalle -> detalle_ps_id);
             $do_producto_stock -> find(true);

             if($do_producto_stock -> ps_cantidad >= $objeto['input_cantidad_dev']) {
              $do_producto_stock -> ps_cantidad = $do_producto_stock -> ps_cantidad - $objeto['input_cantidad_dev'];
              $do_producto_stock -> ps_precio_prom_venta_parcial = 0; //Pongo en 0 el ppv parcial para recalcularlo
              $do_producto_stock -> ps_precio_prom_venta = 0; //como hubo una dev de mercaderia, pongo en 0 el ppv, para que se realice el calculo de nuevo

              $do_producto_stock -> update();
             }
         }
 
         $id_dm = $this -> insert();
 
         $param['input_monto'] = $objeto['input_monto']; 
         $param['nota_fh'] = date('Y-m-d H:i:s');
         $param['id_dm'] = $id_dm;
 
         $do_nota = DB_DataObject::factory('notas');
         $NC = $do_nota -> notaProvDesdeCompra($param);
         
         return $id_dm;
    }

    function getCantProd($venta_id) {

        $detalle = DB_DataObject::factory('venta_detalle');
        $detalle -> detalle_venta_id = $venta_id;
        $detalle -> find();
        

        return $detalle -> N;

    }

    function getCantBultos($venta_id) {
       
        $detalle = DB_DataObject::factory('venta_detalle');
        $detalle -> detalle_venta_id = $venta_id;
        $detalle -> find();

       while ($detalle -> fetch()) {
          $cantBultos = $cantBultos + $detalle -> detalle_prod_cant ;
        }
        
        return $cantBultos;
    }

}
