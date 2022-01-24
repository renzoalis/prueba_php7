<?php
    require_once('../config/web.config');
    require_once(AUTHFILE);
    require_once(CFG_PATH.'/data.config');
    require_once(INC_PATH.'/pear.inc');
    require_once(INC_PATH.'/comun.php');
    $do_venta = DB_DataObject::factory('venta');
    if($_POST['edit_venta']){
        // primero guardo el cliente por si se cambio
         $editar_cliente = $do_venta -> editarCliente($_POST['cliente'],$_POST['edit_venta']);

        // despues la venta y su detalle de productos
        $datos_venta = $do_venta -> editarVenta($_POST);  
        header("Location: movil.php?id_venta_edit=".$datos_venta['id_venta_edit']."&nom_cliente=".$datos_venta['cliente_nombre']."&monto_venta=".$datos_venta['total_venta']);
    }else{        
        //print_r($_POST);exit;
        $datos_venta = $do_venta -> nuevaVenta($_POST);

        header("Location: movil.php?id_venta=".$datos_venta['id']."&nom_cliente=".$datos_venta['nombre']."&monto_venta=".$datos_venta['monto']);
    }

 ?>


