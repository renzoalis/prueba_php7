<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');
	
	// Traigo los permisos del usuario (Ver, Editar, Agregar) en el módulo
	if($_GET['mod_id']){
		$_SESSION['modulo_id'] = $_GET['mod_id'];
	}

	$do_productos_sin_precio = DB_DataObject::factory('producto');
	$cant_sin_precio = $do_productos_sin_precio -> getProductosSinPrecio();

	$permiso = getPermisos($_SESSION['app_id'], $_SESSION['modulo_id'], $_SESSION['usuario']['id']);


	$do_productos_transferencias = DB_DataObject::factory('producto');
	$do_productos_transferencias -> getProductosTransf();

	require_once('public/index.html');
	exit;
?>
