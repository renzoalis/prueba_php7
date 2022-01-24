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

	if($_POST['borrar_cliente']) {
		$cliente = DB_DataObject::factory('cliente');
		$id = $cliente -> eliminarCliente($_POST);
		header("Location: cartera.php?id_eliminar=".$id);  
	}

	if($_POST['add_cliente']) {
		$cliente = DB_DataObject::factory('cliente');
		$id = $cliente -> nuevoCliente($_POST);
		header("Location: cartera.php?id_nuevo=".$id);  
	}

	if($_POST['edit_cliente']) {
		$cliente = DB_DataObject::factory('cliente');
		$id = $cliente -> editCliente($_POST);
		header("Location: cartera.php?id_cliente=".$id); 
	}

	$do_clientes = DB_DataObject::factory('cliente');
	$clientes = $do_clientes -> getClientes();
	$clientes -> orderBy('cliente_fh_alta DESC');
	$clientes -> find();
	
	require_once('public/listado_clientes.html');
	exit;
?>
