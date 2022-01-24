<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$usr = DB_DataObject::factory('usuario');
	$usr -> usua_id = $_SESSION['usuario']['id'];
	$usr -> find(true);

	$premium = $usr -> esPremium();

	if($_POST['borrar_empleado']) {
		$empleado = DB_DataObject::factory('empleado');
		$id = $empleado -> eliminarEmpleado($_POST);
		header("Location: cartera.php?id_eliminar=".$id);  
	}

	if($_POST['add_empleado']) {
		$empleado = DB_DataObject::factory('empleado');
		$id = $empleado -> nuevoEmpleado($_POST);
		header("Location: cartera.php?id_nuevo=".$id);  
	}

	if($_POST['edit_empleado']) {
		$empleado = DB_DataObject::factory('empleado');
		$id = $empleado -> editEmpleado($_POST);
		header("Location: cartera.php?id_empleado=".$id); 
	}

	$do_empleados = DB_DataObject::factory('empleado');
	$empleados = $do_empleados -> getEmpleados();


	require_once('public/listado_empleados.html');
	exit;
?>
