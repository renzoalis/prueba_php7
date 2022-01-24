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
	
	if($_POST['borrar_proveedor']) {
		$proveedor = DB_DataObject::factory('proveedor');
		$id = $proveedor -> eliminarProveedor($_POST);
		header("Location: cartera.php?id_eliminar=".$id);  
	}

	if($_POST['add_proveedor']) {
		$proveedor = DB_DataObject::factory('proveedor');
		$id = $proveedor -> nuevoProveedor($_POST);
		header("Location: cartera.php?id_nuevo=".$id);  
	}

	if($_POST['edit_proveedor']) {
		$proveedor = DB_DataObject::factory('proveedor');
		$id = $proveedor -> editProveedor($_POST);
		header("Location: cartera.php?id_proveedor=".$id); 
	}

	$do_proveedores = DB_DataObject::factory('proveedor');
	$cc_proveedores = DB_DataObject::factory('proveedor_cuenta_corriente');
	$proveedores = $do_proveedores -> getProveedores();

	require_once('public/listado_proveedores.html');
	exit;
?>
