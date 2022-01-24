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

	if($_POST['borrar_producto']){
		$prod = DB_DataObject::factory('producto');
		$prod -> prod_id = $_POST['id_eliminar'];
		$prod -> find(true);
		$prod -> prod_baja = 1;
		$prod -> update(); 
		header("Location: listado.php?id_elim=".$_POST['id_eliminar']); 
	}

	if($_POST['edit_alias_id']){
		$prod = DB_DataObject::factory('producto');
		$prod -> prod_id = $_POST['edit_alias_id'];
		$prod -> find(true);
		$prod -> prod_alias = $_POST['input_alias'];
		$prod -> update(); 
		header("Location: listado.php?id_edit=".$_POST['edit_alias_id']); 
	}

	if($_POST['add_producto']){
		$prod = DB_DataObject::factory('producto');
		$id = $prod -> nuevoProducto($_POST);
		header("Location: listado.php?id_nuevo=".$id); 
	}

	$do_categoria = DB_DataObject::factory('categoria');
	$do_categoria -> cat_baja = 0;
	$do_categoria -> find();

	$producto = DB_DataObject::factory('producto');
	$do_productos = $producto -> getProductos();

	require_once('public/listado_productos.html');
	exit;
?>
