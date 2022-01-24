<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$usr = DB_DataObject::factory('usuario');
	$usr -> usua_id = $_SESSION['usuario']['id'];
	$usr -> find(true);

	$do_caja = DB_DataObject::factory('caja');
	$do_caja -> caja_id = $_POST['id'];
	$do_caja -> find(true);

	$inicio = $do_caja -> caja_fh_inicio;
	$final = false;

	// Resumen diario
	$cobro_cliente = DB_DataObject::factory('cobro_cliente');
    $pagos['Clientes'] = $cobro_cliente -> getIngresosCaja($inicio,$final);

    $pago_proveedor = DB_DataObject::factory('pago_proveedor');
    $pagos['Proveedores'] = $pago_proveedor -> getIngresosCaja($inicio,$final);

    $pago_transportista = DB_DataObject::factory('pago_transportista');
    $pagos['Transportistas'] = $pago_transportista -> getIngresosCaja($inicio,$final);

    $pago_despachante = DB_DataObject::factory('pago_despachante');
    $pagos['Despachantes'] = $pago_despachante -> getIngresosCaja($inicio,$final);

    $pago_importador = DB_DataObject::factory('pago_importador');
    $pagos['Importadores'] = $pago_importador -> getIngresosCaja($inicio,$final);

    $pago_exportador = DB_DataObject::factory('pago_exportador');
    $pagos['Exportadores'] = $pago_exportador -> getIngresosCaja($inicio,$final);

    $pago_empleado = DB_DataObject::factory('pago_empleado');
    $pagos['Empleados'] = $pago_empleado -> getIngresosCaja($inicio,$final);

    $gasto = DB_DataObject::factory('gasto');
    $pagos['Gastos'] = $gasto -> getIngresosCaja($inicio,$final);

    $efectivo['Entradas'] = 0 + $pagos['Clientes']['Efectivo'];
    $efectivo['Salidas'] = 0 + $pagos['Proveedores']['Efectivo'] + $pagos['Transportistas']['Efectivo'] + $pagos['Despachantes']['Efectivo'] + 
    										   $pagos['Importadores']['Efectivo'] + $pagos['Exportadores']['Efectivo'] + $pagos['Empleados']['Efectivo'] + 
    										   $pagos['Gastos']['Efectivo'];

	$cajaTotal = $do_caja -> caja_monto_inicio + $efectivo['Entradas'] - $efectivo['Salidas'];

	$venta = DB_DataObject::factory('venta');
	$datos_ventas = $venta -> getBultosDiariosCaja($do_caja -> caja_fh_inicio, $do_caja -> caja_fh_cierre);

	$cheque = DB_DataObject::factory('cheque');
	$datos_cheque = $cheque -> getIngresosCaja($do_caja -> caja_fh_inicio, $do_caja -> caja_fh_cierre);

	$do_banco_ch3 = DB_DataObject::factory('banco');
	$do_banco_ch3 -> banco_baja = 0;
	$do_banco_ch3 -> find();
	
	$do_cheques_terceros = DB_DataObject::factory('cheque');
	$total_cheques = $do_cheques_terceros -> getMontoTotal();


	$do_cheques_terceros = DB_DataObject::factory('cheque');
	$do_cheques_terceros -> joinAdd($do_banco_ch3);
	$do_cheques_terceros -> whereAdd('cheque_estado = 1');	// Pendiente de cobro o vencido
	$do_cheques_terceros -> find();



	require_once('public/modales/cerrarCaja.html');

	exit;
?>