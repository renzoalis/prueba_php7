<?php
/**
 * Table Definition for compra_concepto
 */
require_once 'DB/DataObject.php';

class DataObjects_Compra_concepto extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'compra_concepto';     // table name
    public $cc_op_id;                        // int(11)  not_null group_by
    public $cc_id;                           // int(11)  not_null primary_key auto_increment group_by
    public $cc_compra_id;                    // int(11)  not_null group_by
    public $cc_tipo;                         // int(11)  not_null group_by
    public $cc_observacion;                  // varchar(245)  
    public $cc_fh;                           // datetime(19)  
    public $cc_monto;                        // float(11)  group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Compra_concepto',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function nuevoConcepto($objeto) {
        //print_r($objeto);exit;
        $this -> cc_compra_id = $objeto['concepto_compra_id'];
        $this -> cc_tipo = $objeto['combo_tipo'];       //compra_concepto_tipo
        $this -> cc_fh = date('Y-m-d H:i:s');
        $this -> cc_monto = $objeto['input_monto'];
        $this -> cc_observacion = $objeto['input_obs_concepto'];

        $compra = DB_DataObject::factory('compra');
        $compra -> compra_id = $objeto['concepto_compra_id'];
        $compra -> find(true);

        $params['input_id_prov'] = $compra -> compra_prov_id;
        $params['input_id_transp'] = $compra -> compra_transp_id;
        $params['compra_id'] = $objeto['concepto_compra_id'];
        $params['input_monto'] = $objeto['input_monto'];  
        $params['concepto_fh'] = date('Y-m-d H:i:s'); 
        $params['input_obs_concepto'] = $objeto['input_obs_concepto']; 
        $params['input_obs_devolver'] = $objeto['input_obs_devolver']; 
        $params['combo_tipo']= $objeto['combo_tipo'];

        if($objeto['combo_tipo'] == 7 ) {      //OTROS GASTOS
            // print_r($objeto);exit;
            $compra -> compra_concepto_impuestos = $compra -> compra_concepto_impuestos + $objeto['input_monto'];
            $compra -> update();

            $params['input_monto_total'] = $objeto['input_monto'];
            $params['categoria_id'] = 6;   //OTROS
            $params['input_concepto'] = $objeto['input_obs_concepto'];
            $params['obs'] = 'Gasto en compra '.$objeto['concepto_compra_id'];

            $gasto = DB_DataObject::factory('gasto');
            $gasto -> nuevoGasto($params);

        }

        if($objeto['combo_tipo'] == 1 ) {    //COSTO DESCARGA
          //Costo Descarga
            $params['combo_tipo_nombre'] = 'Costo Descarga';
            $params['input_monto_total'] = $objeto['input_monto'];
            $params['input_comprob'] = $objeto['input_comprob'];

            //$params['compra_detalle_id'] = $objeto['concepto_compra_detalle_id'];
            $descarga = DB_DataObject::factory('descarga');
            $id_op = $descarga -> nuevaDescarga($params);

            $compra -> compra_concepto_descargas = $compra -> compra_concepto_descargas + $objeto['input_monto'];
            $compra -> update();
            // guardo el detalle de los productos que se descargaron    
            foreach ($objeto['prod'] as $p) {

                $detalle = DB_DataObject::factory('descarga_detalle');
                $detalle -> detalle_descarga_id = $id_op;
                $detalle -> detalle_descarga_prod = $p['detalle_prod'];
                $detalle -> detalle_descarga_costo = $p['costo'];
                $detalle -> detalle_descarga_cant = $p['cant'];
                $detalle -> detalle_ps_id = $p['ps_id'];
                $detalle -> insert();

                // $do_producto_stock = DB_DataObject::factory('producto_stock');
                // $do_producto_stock -> ps_id = $p['ps_id'];
                // $do_producto_stock -> find(true);

                // //ACTUALIZO EL PRECIO UNITARIO DE COSTO DEL PRODUCTO
                // $do_producto_stock -> ps_costo_u = $do_producto_stock -> ps_costo_u + $p['costo'];
                // $do_producto_stock -> update();
                

            }
        }

    /*    if($objeto['combo_tipo'] == 2){        //Entrega Transportista
            $params['combo_tipo_nombre'] = 'Entrega Transportista';    
            $params['combo_fpago']= 1;  //Efectivo
            $params['input_obs_pago'] = $objeto['input_obs_concepto']; 
            $pago_transp = DB_DataObject::factory('pago_transportista');
            $id_op = $pago_transp -> nuevoPago($params);
        }*/

        if($objeto['combo_tipo'] == 3){        //costo Flete
            $params['combo_tipo_nombre'] = 'Costo Flete';  
            $params['input_monto_total'] = $objeto['input_monto'];
            $params['input_comprob'] = $objeto['input_comprob'];

            //$params['compra_detalle_id'] = $objeto['concepto_compra_detalle_id'];
            $flete = DB_DataObject::factory('flete');
            $id_op = $flete -> nuevoFlete($params);
            $compra -> compra_concepto_fletes = $compra -> compra_concepto_fletes + $objeto['input_monto'];
            $compra -> update();

            // guardo el detalle de los productos    
            foreach ($objeto['prod'] as $p) {
                $detalle = DB_DataObject::factory('flete_detalle');
                $detalle -> detalle_flete_id = $id_op;
                $detalle -> detalle_prod_id = $p['id'];
                $detalle -> detalle_prod_cant = $p['cantidad'];
                $detalle -> detalle_prod_costo_u = $p['costo'];
                $detalle -> detalle_prod_calibre = $p['calibre'];
                $detalle -> detalle_ps_id = $p['ps_id'];
                $detalle -> insert();
                
                // $do_producto_stock = DB_DataObject::factory('producto_stock');
                // $do_producto_stock -> ps_id = $p['ps_id'];
                // $do_producto_stock -> find(true);

                // //ACTUALIZO EL PRECIO UNITARIO DE COSTO DEL PRODUCTO
                // $do_producto_stock -> ps_costo_u = $do_producto_stock -> ps_costo_u + $p['costo'];
                // $do_producto_stock -> update();


            }

        }

       /* if($objeto['combo_tipo'] == 5){        //Nota Credito
            $params['combo_tipo_nombre'] = 'Nota Credito';  
            $params['nota_tipo'] = 'NC';  
            $notas = DB_DataObject::factory('notas');
            $id_op = $notas -> notaProvDesdeCompra($params);
        }*/

       /* if($objeto['combo_tipo'] == 6){        //Nota Debito
            $params['combo_tipo_nombre'] = 'Nota Debito';    
            $params['nota_tipo'] = 'ND';  
            $notas = DB_DataObject::factory('notas');
            $id_op = $notas -> notaProvDesdeCompra($params);
        }*/

        if($objeto['combo_tipo'] == 8){                                    // DEVOLUCION MERCADERIA
            $dev_mercaderia = DB_DataObject::factory('devolucion_mercaderia');
            $id_op = $dev_mercaderia -> devolverMercaderiaCompra($objeto);
        }
        /*if($objeto['combo_tipo'] == 9 ) {      //Costo Carga
            $params['combo_tipo_nombre'] = 'Costo Carga';
            $params['input_cantidad'] = $objeto['input_cantidad'];
            $params['input_costo_unitario'] = $objeto['input_costo_unitario'];
            $carga = DB_DataObject::factory('carga');
            $id_op = $carga -> nuevaCarga($params);
            $compra -> compra_concepto_cargas = $compra -> compra_concepto_cargas + ($objeto['input_cantidad'] * $objeto['input_costo_unitario']);
            $compra -> update();
        }*/

       if($objeto['combo_tipo'] == 13 ) {      //Costo Mercadería
            $params['combo_tipo_nombre'] = 'Costo Mercadería';
            $params['input_monto_total'] = $objeto['input_monto'];
            $params['input_comprob'] = $objeto['input_comprob'];
            $params['input_obs_concepto'] = $objeto['input_obs_concepto'];
            $params['input_prov_id'] = $objeto['input_prov_id'];
            // $params['input_obs_concepto'] = $objeto['input_obs_concepto'];
            
            //$params['compra_detalle_id'] = $objeto['concepto_compra_detalle_id'];
            $costo_mercaderia = DB_DataObject::factory('costo_mercaderia');
            $id_op = $costo_mercaderia -> nuevo_costo_mercaderia($params);


            foreach ($objeto['prod'] as $p) {
                // print_r();exit;
                //Actualizo el precio en la compra
                if($p['calibre'] == ""){
                    $p['calibre'] = "S/C";
                }
                $compra_detalle = DB_DataObject::factory('compra_detalle');
                // $compra_detalle -> whereAdd(' detalle_compra_id = '.$objeto['concepto_compra_id'].' and detalle_prod_id='.$p['id'].' and detalle_prod_calibre ="'.$p['calibre'].'"');
                $compra_detalle -> whereAdd('detalle_ps_id = '.$p['ps_id']);
                $compra_detalle -> find(true);
                $compra_detalle -> detalle_prod_precio_u = $p['costo'];
                $compra_detalle -> update();


                //Actualizo el precio del lote
                $producto_stock = DB_DataObject::factory('producto_stock');
                $producto_stock -> whereAdd('ps_id = '.$p['ps_id']);
                $producto_stock -> find(true);

                $producto_stock -> ps_costo_u = $producto_stock -> ps_costo_u + $p['costo'];
                
                $producto_stock -> update();


                $detalle = DB_DataObject::factory('costo_mercaderia_detalle');
                $detalle -> detalle_cm_id = $id_op;
                $detalle -> detalle_cm_prod = $p['detalle_prod'];
                $detalle -> detalle_cm_costo = $p['costo'];
                $detalle -> detalle_cm_cant = $p['cant'];
                $detalle -> detalle_ps_id = $p['ps_id'];
                $detalle -> insert();
                    
            }
            

            //Actualizo el monto total de la compra
            $compra_actualizar = DB_DataObject::factory('compra');
            $compra_actualizar -> compra_id = $objeto['concepto_compra_id'];
            $compra_actualizar -> find(true);
            $actualizar_monto_compra = $compra_actualizar -> actualizarMontoTotal();


        }

        $this -> cc_op_id = $id_op;     //Guardo el Id de la operacion

        $id_concepto = $this -> insert();
    }

    function getTipo($id){
         $do_tipo = DB_DataObject::factory('compra_concepto_tipo');
         $do_tipo -> cc_tipo_id = $this -> cc_tipo;
         $do_tipo -> find(true);
        
         if($this -> cc_tipo == 8){
             $dev_mercaderia = DB_DataObject::factory('devolucion_mercaderia');
             $dev_mercaderia -> dev_id = $this -> cc_op_id;
             $dev_mercaderia -> find(true);
             $texto = ' ('.$dev_mercaderia -> dev_cantidad.' x '.$dev_mercaderia -> dev_obs.')';
             
         }elseif($this -> cc_tipo == 1){
             $descarga = DB_DataObject::factory('descarga');
             $descarga -> desc_id = $this -> cc_op_id;
             $descarga -> find(true);
            // $texto = ' ('.$descarga -> desc_cantidad.' x '.$descarga -> desc_descripcion.') COMP. '.$descarga -> desc_comprob;
             if ($descarga -> desc_comprob){
             $texto = ' COMP.'.$descarga -> desc_comprob;}
             
         }elseif($this -> cc_tipo == 3){
             $flete = DB_DataObject::factory('flete');
             $flete -> flete_id = $this -> cc_op_id;
             $flete -> find(true);
            // $texto = ' ('.$descarga -> desc_cantidad.' x '.$descarga -> desc_descripcion.') COMP. '.$descarga -> desc_comprob;
             if ($flete -> flete_comprob){
             $texto = ' COMP.'.$flete -> flete_comprob;}
             
         }else{
            $texto = '';
         }

         return $do_tipo -> cc_tipo_nombre.$texto;
    }
}
