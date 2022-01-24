<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');


	$salidas_de_caja = DB_Dataobject::factory('view_salidas_de_caja');
	$salidas_de_caja -> whereAdd('id = '.$_POST['id'].' AND categoria = "'.$_POST['categoria'].'"');
	$salidas_de_caja -> find(true);
	
	//print_r($_POST);exit;
	if($salidas_de_caja -> categoria == "Banco"){	//Cobros a clientes que ingresan por banco
		// DB_Dataobject::debugLevel(5);
		$detalle = DB_Dataobject::factory('cobro_cliente');
		$cliente = DB_Dataobject::factory('cliente');
		$cheque_terceros = DB_Dataobject::factory('cheque');
		$boleto = DB_Dataobject::factory('boleto');
		$deposito = DB_Dataobject::factory('deposito_bancario_terceros');
		$transferencia = DB_Dataobject::factory('transferencia_bancaria_terceros');

		$detalle -> joinAdd($cliente, "LEFT");
		$detalle -> joinAdd($cheque_terceros, "LEFT");
		$detalle -> joinAdd($boleto, "LEFT");
		$detalle -> joinAdd($deposito, "LEFT");
		$detalle -> joinAdd($transferencia, "LEFT");

		$detalle -> whereAdd('cobro_id = '.$_POST['id']);
		$detalle -> find(true);
		$detalle -> nombre_operador = $detalle -> cliente_nombre;

		// print_r($cobro_cliente);exit;
	}

	if($salidas_de_caja -> categoria == "Retiro Cheque"){	//Salidas de caja por retiro de cheque
		 // DB_Dataobject::debugLevel(5);
		$detalle = DB_Dataobject::factory('cheque');
		$cliente = DB_Dataobject::factory('cliente');

		$detalle -> joinAdd($cliente, "LEFT");

		$detalle -> whereAdd('cheque_id = '.$_POST['id']);
		$detalle -> find(true);
		$detalle -> nombre_operador = $detalle -> cliente_nombre;

		 // print_r($detalle);exit;
	}

	if($salidas_de_caja -> categoria == "Proveedores"){	//Pagos a Proveedores
		// DB_Dataobject::debugLevel(5);
		$detalle = DB_Dataobject::factory('pago_proveedor');
		$proveedor = DB_Dataobject::factory('proveedor');
		$cheque_propio = DB_Dataobject::factory('cheque_propio');
		$cheque_terceros = DB_Dataobject::factory('cheque');
		$boleto = DB_Dataobject::factory('boleto');
		$deposito = DB_Dataobject::factory('deposito_bancario');
		$transferencia = DB_Dataobject::factory('transferencia_bancaria');

		$detalle -> joinAdd($proveedor, "LEFT");
		$detalle -> joinAdd($cheque_terceros, "LEFT");
		$detalle -> joinAdd($cheque_propio, "LEFT");
		$detalle -> joinAdd($boleto, "LEFT");
		$detalle -> joinAdd($deposito, "LEFT");
		$detalle -> joinAdd($transferencia, "LEFT");

		$detalle -> whereAdd('pago_id = '.$_POST['id']);
		$detalle -> find(true);
		$detalle -> nombre_operador = $detalle -> prov_nombre;

		 // print_r($pago_proveedor);exit;
	}

	if($salidas_de_caja -> categoria == "Transportistas"){	//Pagos a Transportistas
		 // DB_Dataobject::debugLevel(5);
		$detalle = DB_Dataobject::factory('pago_transportista');
		$transportista = DB_Dataobject::factory('transportista');
		$cheque_propio = DB_Dataobject::factory('cheque_propio');
		$cheque_terceros = DB_Dataobject::factory('cheque');
		$deposito = DB_Dataobject::factory('deposito_bancario');
		$transferencia = DB_Dataobject::factory('transferencia_bancaria');

		$detalle -> joinAdd($transportista, "LEFT");
		$detalle -> joinAdd($cheque_terceros, "LEFT");
		$detalle -> joinAdd($cheque_propio, "LEFT");
		$detalle -> joinAdd($deposito, "LEFT");
		$detalle -> joinAdd($transferencia, "LEFT");

		$detalle -> whereAdd('pago_id = '.$_POST['id']);
		$detalle -> find(true);
		$detalle -> nombre_operador = $detalle -> transportista_nombre;
		 // print_r($detalle);exit;
	}

	if($salidas_de_caja -> categoria == "Carga/Descarga"){	//Pagos a CarDesc
		//DB_Dataobject::debugLevel(5);
		$detalle = DB_Dataobject::factory('pago_cardesc');
		$cardesc = DB_Dataobject::factory('cardesc');
		$cheque_propio = DB_Dataobject::factory('cheque_propio');
		$cheque_terceros = DB_Dataobject::factory('cheque');
		$deposito = DB_Dataobject::factory('deposito_bancario');
		$transferencia = DB_Dataobject::factory('transferencia_bancaria');

		$detalle -> joinAdd($cardesc, "LEFT");
		$detalle -> joinAdd($cheque_terceros, "LEFT");
		$detalle -> joinAdd($cheque_propio, "LEFT");
		$detalle -> joinAdd($deposito, "LEFT");
		$detalle -> joinAdd($transferencia, "LEFT");

		$detalle -> whereAdd('pago_id = '.$_POST['id']);
		$detalle -> find(true);
		$detalle -> nombre_operador = $detalle -> cardesc_nombre;

		 // print_r($detalle);exit;
	}


	if($salidas_de_caja -> categoria == "Entrega Empleado"){	//Pagos a empleados
		 // DB_Dataobject::debugLevel(5);
		$detalle = DB_Dataobject::factory('pago_empleado');
		$empleados = DB_Dataobject::factory('empleado');
		// $cheque_propio = DB_Dataobject::factory('cheque_propio');
		// $cheque_terceros = DB_Dataobject::factory('cheque');
		// $deposito = DB_Dataobject::factory('deposito_bancario');
		// $transferencia = DB_Dataobject::factory('transferencia_bancaria');

		$detalle -> joinAdd($empleados, "LEFT");
		// $detalle -> joinAdd($cheque_terceros, "LEFT");
		// $detalle -> joinAdd($cheque_propio, "LEFT");
		// $detalle -> joinAdd($deposito, "LEFT");
		// $detalle -> joinAdd($transferencia, "LEFT");

		$detalle -> whereAdd('pago_id = '.$_POST['id']);
		$detalle -> find(true);
		$detalle -> nombre_operador = $detalle -> empleado_nombre;

		//print_r($detalle);exit;
	}


	if($salidas_de_caja -> categoria == "Gastos"){	//Pagos a empleados
		 // DB_Dataobject::debugLevel(5);
		$detalle = DB_Dataobject::factory('gasto');

		$detalle -> whereAdd('gasto_id = '.$_POST['id']);
		$detalle -> find(true);
		$detalle -> nombre_operador = $detalle -> gasto_concepto;

		//print_r($detalle);exit;
	}

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

	require_once('public/modales/edit_gasto.html');
	exit;
?>