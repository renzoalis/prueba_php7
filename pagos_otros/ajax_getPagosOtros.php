<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_pagos_otros = DB_Dataobject::factory('view_pagos_otros');
	$do_pagos_otros -> whereAdd('ID = '.$_POST['id'].' AND ENTIDAD_ID = '.$_POST['categoria'].'');
	$do_pagos_otros -> find(true);
	
	// print_r($do_pagos_otros);exit;
	if($do_pagos_otros -> ENTIDAD == "PROVEEDOR"){	//Pagos a Proveedores
		// DB_Dataobject::debugLevel(5);
		$pago = DB_DataObject::factory('proveedor');
		$pago -> whereAdd('prov_id = '.$do_pagos_otros-> ENTIDAD_ID);
		$pago -> find(true);

		$pago -> nombre_entidad = $pago -> prov_nombre;
	}

	if($do_pagos_otros -> ENTIDAD == "TRANSPORTISTA"){	//Pagos a Transportistas
		
		$pago = DB_DataObject::factory('transportista');

		$pago -> whereAdd('transportista_id = '.$do_pagos_otros-> ENTIDAD_ID);
		$pago -> find(true);
		$pago -> nombre_entidad = $pago -> transportista_nombre;


	}

	if($do_pagos_otros -> ENTIDAD == "CARGA/DESCARGA"){	//Pagos a CarDesc
		//DB_Dataobject::debugLevel(5);
		$pago = DB_DataObject::factory('cardesc');
		$pago -> whereAdd('cardesc_id = '.$do_pagos_otros-> ENTIDAD_ID);
		$pago -> find(true);

		$pago -> nombre_entidad = $pago -> cardesc_nombre;

		
		 // print_r($detalle);exit;
	}


	// if($do_pagos_otros -> ENTIDAD == "EMPLEADO"){	//Pagos a empleados
		 // DB_Dataobject::debugLevel(5);
		// $detalle = DB_Dataobject::factory('pago_empleado');
		// $empleados = DB_Dataobject::factory('empleado');
		// // $cheque_propio = DB_Dataobject::factory('cheque_propio');
		// // $cheque_terceros = DB_Dataobject::factory('cheque');
		// // $deposito = DB_Dataobject::factory('deposito_bancario');
		// // $transferencia = DB_Dataobject::factory('transferencia_bancaria');

		// $detalle -> joinAdd($empleados, "LEFT");
		// // $detalle -> joinAdd($cheque_terceros, "LEFT");
		// // $detalle -> joinAdd($cheque_propio, "LEFT");
		// // $detalle -> joinAdd($deposito, "LEFT");
		// // $detalle -> joinAdd($transferencia, "LEFT");

		// $detalle -> whereAdd('pago_id = '.$_POST['id']);
		// $detalle -> find(true);
		// $detalle -> nombre_operador = $detalle -> empleado_nombre;

		//print_r($detalle);exit;
	// }

	// if($do_pagos_otros -> ENTIDAD == "DESPACHANTE"){	//Salidas de caja por retiro de cheque
	// 	 // DB_Dataobject::debugLevel(5);
	// 	$detalle = DB_Dataobject::factory('cheque');
	// 	$cliente = DB_Dataobject::factory('cliente');

	// 	$detalle -> joinAdd($cliente, "LEFT");

	// 	$detalle -> whereAdd('cheque_id = '.$_POST['id']);
	// 	$detalle -> find(true);
	// 	$detalle -> nombre_operador = $detalle -> cliente_nombre;

	// 	 // print_r($detalle);exit;
	// }

	// if($do_pagos_otros -> ENTIDAD == "Gastos"){	//Pagos a empleados
		 // DB_Dataobject::debugLevel(5);
		// $detalle = DB_Dataobject::factory('gasto');

		// $detalle -> whereAdd('gasto_id = '.$_POST['id']);
		// $detalle -> find(true);
		// $detalle -> nombre_operador = $detalle -> gasto_concepto;

		//print_r($detalle);exit;
	// }

	require_once('public/modales/edit_gasto.html');
	exit;
?>