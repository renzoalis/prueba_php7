<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

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

	$pago_aux = DB_DataObject::factory('pago_empleado');
	$pago_aux -> whereAdd('pago_id = '.$_POST['id']);
	$pago_aux -> find(true);

	$pago = DB_DataObject::factory('pago_empleado');
	$pago -> whereAdd('pago_id = '.$_POST['id']);
     
	
	$do_empleado = DB_DataObject::factory('empleado');
	$cheque_propio = DB_DataObject::factory('cheque_propio');
	$cheque = DB_DataObject::factory('cheque');
	$transferencia_bancaria_terceros = DB_DataObject::factory('transferencia_bancaria');
	$deposito_bancario_terceros = DB_DataObject::factory('deposito_bancario');


	$pago -> joinAdd($do_empleado);

	if($pago_aux -> pago_forma_pago == 2){				//CHEQUE PROPIO
		$pago -> joinAdd($cheque_propio,"LEFT");
	}elseif($pago_aux -> pago_forma_pago == 6) {		//CHEQUE PROPIO
		$pago -> joinAdd($cheque,"LEFT");
	}
	
	$pago -> joinAdd($transferencia_bancaria_terceros,"LEFT");
	$pago -> joinAdd($deposito_bancario_terceros,"LEFT");
	//$boleto = DB_DataObject::factory('boleto');
	//$pago -> joinAdd($boleto,"LEFT");

	$meses[1] = "Enero";
	$meses[2] = "Febrero";
	$meses[3] = "Marzo";
	$meses[4] = "Abril";
	$meses[5] = "Mayo";
	$meses[6] = "Junio";
	$meses[7] = "Julio";
	$meses[8] = "Agosto";
	$meses[9] = "Septiembre";
	$meses[10] = "Octubre";
	$meses[11] = "Noviembre";
	$meses[12] = "Diciembre";

	$pago -> find(true);

	$do_empleado = DB_DataObject::factory('empleado');
	$do_empleado -> empleado_baja = 0;
	$do_empleado -> orderBy('empleado_nombre ASC');
	$do_empleado -> find();
	$empleados = array();
    //print_r($pago);exit;
	while ($do_empleado -> fetch()) { 
		$empleados[$do_empleado -> empleado_id]['id'] = $do_empleado -> empleado_id;
		$empleados[$do_empleado -> empleado_id]['nombre'] = $do_empleado -> empleado_nombre;
	}
    // print_r($empleados);exit;
	require_once('public/modales/ver_pago.html');
	exit;
?>