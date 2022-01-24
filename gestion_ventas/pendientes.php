<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');
	
	if($_POST['Eliminar']) {

		$venta_e = DB_DataObject::factory('venta');
		$id_deleted = $venta_e -> eliminarVenta($_POST);
		header("Location: pendientes.php?id_venta_elim=".$id_deleted);
 	}
 
 	// Post editar venta
     
 	if($_POST['edit_venta'] && !isset($_POST['Eliminar']) ){
 		$do_venta = DB_DataObject::factory('venta');
        $datos_venta = $do_venta -> editarVenta($_POST);  
        header("Location: pendientes.php?id_venta_abrir=".$datos_venta['id_venta_edit']);
	}

	// Post agregar nuevo cliente en venta

	if($_POST['add_cliente']){
		//Guardo el nuevo cliente
 		$do_cliente = DB_DataObject::factory('cliente');
        $cliente_id = $do_cliente -> nuevoCliente($_POST);
        // lo asocio a la venta
        $do_venta = DB_DataObject::factory('venta');
		$id = $do_venta -> editarCliente($cliente_id,$_POST['add_cliente_venta_id']); 
		// abro el modal de la venta 
        header("Location: pendientes.php?id_venta_abrir=".$_POST['add_cliente_venta_id']);
	}

	if($_POST['nuevo_cobro']) {

		//Agrego la venta a la CC
		$venta = DB_DataObject::factory('venta');
        $venta -> venta_id = $_POST['venta_id'];
        $venta -> find(true);

		$objeto['cliente'] = $_POST['input_id_cliente'];
		$total_venta = $venta -> venta_monto_total - $venta -> venta_descuento_total;   //Calculo el total de la venta - descuentos
        
        $cc_cliente = DB_DataObject::factory('cliente_cuenta_corriente');
        $cc_cliente -> cargarVenta($objeto,$venta -> venta_id,$total_venta);

		$cobro = DB_DataObject::factory('cobro_cliente');
		$id = $cobro -> nuevoCobro($_POST);
		$monto = $_POST['input_monto_contado'] + $_POST['input_monto_cheque'] + $_POST['input_monto_credito'] + $_POST['input_monto_debito'] + $_POST['input_monto_pesos_boleto'] + $_POST['input_monto_transfer'] + $_POST['input_monto_deposito'];

		if($id) {
			$v = DB_DataObject::factory('venta');
			$v -> venta_id = $_POST['venta_id'];
			$v -> find(true);
			$v -> venta_estado_id = 2;
			$v -> venta_monto_contado = $monto; // Usamos este campo para mostrar el cobro.
			$v -> venta_forma_pago_id = $_POST['combo_fpago'];

			$v -> update();
		}
		
		header("Location: pendientes.php?id_cobro=".$id); 
	}

	if($_POST['nuevo_descuento']) {
		//print_r($_POST);exit;

		$venta_concepto = DB_DataObject::factory('venta_concepto');
		$id = $venta_concepto -> nuevoDescuento($_POST);

		if($id) {
			$id_venta = $_POST['desc_venta_id'];
		}
		
		header("Location: pendientes.php?id_descuento=".$id_venta."&abrir_venta=".$id_venta); 
	}


	$do_cli = DB_DataObject::factory('cliente');
	$do_cli -> cliente_baja = 0;
	$do_cli -> find();

	$do_banco = DB_DataObject::factory('banco');
	$do_banco -> banco_baja = 0;
	$do_banco -> find();

	$do_banco2 = DB_DataObject::factory('banco');
	$do_banco2 -> banco_baja = 0;
	$do_banco2 -> find();

	$do_banco_et = DB_DataObject::factory('banco');
	$do_banco_et -> banco_baja = 0;
	$do_banco_et -> find();

	$do_banco_rt = DB_DataObject::factory('banco');
	$do_banco_rt -> banco_baja = 0;
	$do_banco_rt -> find();

	$do_banco_d = DB_DataObject::factory('banco');
	$do_banco_d -> banco_baja = 0;
	$do_banco_d -> find();

	$clientes = array();

	while ($do_cli -> fetch()) { 
		$clientes[$do_cli -> cliente_id]['id'] = $do_cli -> cliente_id;
		$clientes[$do_cli -> cliente_id]['nombre'] = $do_cli -> cliente_nombre;
	}

	$venta = DB_DataObject::factory('venta');

	$caja = DB_DataObject::factory('caja');
	$cajaAbierta = $caja -> cajaAbiertaHoy();

	if(!$_GET['fecha_desde']){
		$ultima_caja = $caja -> getUltimaCaja();
		$f_desde = $ultima_caja -> caja_fh_inicio;
		$f_hasta = date('Y-m-d 23:59:59');

		$do_ventas = $venta -> getVentasPendientes($f_desde,date('Y-m-d H:i:s'));
		$campoFecha = date('d/m/Y',strtotime($f_desde)).' - '.date('d/m/Y');
	} else {
		$do_ventas = $venta -> getVentasPendientes($_GET['fecha_desde'],$_GET['fecha_hasta']);
		$campoFecha = date('d/m/Y',strtotime($_GET['fecha_desde'])).' - '.date('d/m/Y',strtotime($_GET['fecha_hasta']));
	}

	
	require_once('public/pendientes.html');
	exit;
?>
