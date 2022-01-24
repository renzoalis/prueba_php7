<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_emplead = DB_DataObject::factory('empleado');
	$do_emplead -> empleado_baja = 0;
	$do_emplead -> orderBy('empleado_nombre ASC');
	$do_emplead -> find();

	$empleados = array();

	while ($do_emplead -> fetch()) { 
		$empleados[$do_emplead -> empleado_id]['id'] = $do_emplead -> empleado_id;
		$empleados[$do_emplead -> empleado_id]['nombre'] = $do_emplead -> empleado_nombre;
	}

	if(!$_GET['fecha_desde']){
		$fecha_actual = new DateTime();	
		$f_desde =  $fecha_actual -> modify("-1 month");
		$campoFecha = date_format($f_desde,'d/m/Y').' - '.date('d/m/Y');
	}

	if($_GET['id_empleado']) {

		$ccte = DB_DataObject::factory('empleado_cuenta_corriente');

		if(!$_GET['fecha_desde']){
			$fecha_actual = new DateTime();	
			$f_desde =  $fecha_actual -> modify("-1 month");
			$dsd = date_format($f_desde,'d/m/Y');
			$hst = date('d/m/Y');
			$cc = $ccte -> empleadoGetCC($_GET['id_empleado'],$dsd,$hst);
			$campoFecha = date_format($f_desde,'d/m/Y').' - '.date('d/m/Y');
		} else {
			$cc = $ccte -> empleadoGetCC($_GET['id_empleado'],$_GET['fecha_desde'],$_GET['fecha_hasta']);
			$campoFecha = $_GET['fecha'];
		}

		$ccte2 = DB_DataObject::factory('empleado_cuenta_corriente');
		$saldo = $ccte2 -> getUltimaCC($_GET['id_empleado']);
		if($saldo -> ccte_saldo_actual < 0) {
			$class_cc = "cc_rojo";
		} else {
			$class_cc = "cc_verde";
		}
	}

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

	require_once('public/cc_empleados.html');
	exit;
?>
