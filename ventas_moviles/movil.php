<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

    $caja = DB_DataObject::factory('caja');
    $cajaAbierta = $caja -> cajaAbiertaHoy();

    if(!$cajaAbierta) {
        require_once('public/caja_cerrada.html');
        exit;
    }

    $do_venta = DB_DataObject::factory('venta');
    $ventas = $do_venta -> getListadoVentas(1);
    //print_r($ventas);exit;

    $procesadas = array();
    while($ventas -> fetch()){

        $do_venta_detalle = DB_DataObject::factory('venta_detalle');
        $do_venta_detalle -> detalle_venta_id = $ventas -> venta_id;

        $do_producto = DB_DataObject::factory('producto');
        $do_categoria = DB_DataObject::factory('categoria');
        $do_tipo = DB_DataObject::factory('tipo');

        $do_categoria -> joinAdd($do_tipo);
        $do_producto -> joinAdd($do_categoria);

        $do_venta_detalle -> joinAdd($do_producto);

        $do_venta_detalle -> find();

        $do_usuario = DB_DataObject::factory('usuario');
        $do_usuario -> usua_id = $ventas -> venta_usuario_id;
        $do_usuario -> find(true);
        // print_r($do_venta_detalle);exit;

        $procesadas[$ventas -> venta_id]['Venta'] = $ventas -> venta_id;
        $procesadas[$ventas -> venta_id]['Usuario'] = $do_usuario -> usua_nombre;
        $procesadas[$ventas -> venta_id]['Fecha'] = $ventas -> venta_fh;
        if($ventas -> cliente_id){
            $procesadas[$ventas -> venta_id]['Cliente'] = $ventas -> cliente_nombre;
            $procesadas[$ventas -> venta_id]['cliente_id'] = $ventas -> cliente_id;
        };
        $procesadas[$ventas -> venta_id]['Bultos'] = $ventas -> getCantBultos();
         $procesadas[$ventas -> venta_id]['Productos'] = $do_venta_detalle -> N;
        $procesadas[$ventas -> venta_id]['Total'] = $ventas -> venta_monto_total;


        while ($do_venta_detalle -> fetch()) {
            $procesadas[$ventas -> venta_id]['Compra'][$do_venta_detalle -> detalle_id]['prod_id'] = $do_venta_detalle -> detalle_prod_id;
            $procesadas[$ventas -> venta_id]['Compra'][$do_venta_detalle -> detalle_id]['cat_id'] = $do_venta_detalle -> cat_nombre;
            $procesadas[$ventas -> venta_id]['Compra'][$do_venta_detalle -> detalle_id]['prod_nombre'] = $do_venta_detalle -> prod_alias;
            $procesadas[$ventas -> venta_id]['Compra'][$do_venta_detalle -> detalle_id]['prod_tipo'] = $do_venta_detalle -> tipo_desc;
            $prod = DB_DataObject::factory('producto');
            $prod -> prod_id = $do_venta_detalle -> detalle_prod_id;
            $max = $prod -> getStock($do_venta_detalle -> detalle_prod_calibre) + $do_venta_detalle -> detalle_prod_cant;
            $procesadas[$ventas -> venta_id]['Compra'][$do_venta_detalle -> detalle_id]['prod_max'] = $max;
            $procesadas[$ventas -> venta_id]['Compra'][$do_venta_detalle -> detalle_id]['prod_calibre'] = $do_venta_detalle -> detalle_prod_calibre;
            $procesadas[$ventas -> venta_id]['Compra'][$do_venta_detalle -> detalle_id]['prod_lote'] = $do_venta_detalle -> detalle_prod_lote;
            $procesadas[$ventas -> venta_id]['Compra'][$do_venta_detalle -> detalle_id]['prod_cant'] = $do_venta_detalle -> detalle_prod_cant;
            $procesadas[$ventas -> venta_id]['Compra'][$do_venta_detalle -> detalle_id]['prod_val'] = $do_venta_detalle -> detalle_prod_precio_u;
            $procesadas[$ventas -> venta_id]['Compra'][$do_venta_detalle -> detalle_id]['pord_tot'] = $do_venta_detalle -> detalle_prod_precio_u * $do_venta_detalle -> detalle_prod_cant;

        }

        //print_r($procesadas);exit;
    }    


    
    $do_tipo =  DB_DataObject::factory('tipo');
    $do_tipo -> find();
    $tipos = array();
    $i = 0;
    while($do_tipo ->fetch()){
        $tipos[$i]['id'] = $do_tipo -> tipo_id;
        $tipos[$i]['nombre'] = $do_tipo -> tipo_nombre;
        $i++;
    }


    $do_cli = DB_DataObject::factory('cliente');
    $do_cli -> cliente_baja = 0;
    $do_cli -> orderBy('cliente_nombre ASC');
    $do_cli -> find();
   
    $clientes = array();

    while ($do_cli -> fetch()) { 
        $clientes[$do_cli -> cliente_id]['id'] = $do_cli -> cliente_id;
        $clientes[$do_cli -> cliente_id]['nombre'] = $do_cli -> cliente_nombre;
    }

    // compruebo si es de rol vendedor
    $usr = DB_DataObject::factory('usuario');
    $usr -> usua_id = $_SESSION['usuario']['id'];
    $usr -> find(true);
    $vendedor = $usr -> esUsuarioBasic();



	require_once('public/venta_movil.html');
	exit;
?>
