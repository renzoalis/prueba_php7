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
	$permiso = getPermisos($_SESSION['app_id'], $_SESSION['modulo_id'], $_SESSION['usuario']['id']);
	//DB_DataObject::debugLevel(5);

	if($_POST['edit_categoria']) {// Editar categoria
		// print_r($_POST);exit;
		$do_categoria = DB_DataObject::factory('categoria');
		$id_cat_edit = $do_categoria -> edit_categoria($_POST);
		header("Location: categorias.php?id_categoria=".$id_cat_edit);

	}
	if($_POST['add_categoria']) { // Alta categoria
		
		$do_categoria = DB_DataObject::factory('categoria');
		$id = $do_categoria -> alta_categoria($_POST);
		header("Location: categorias.php?id_nuevo=".$id);

	}

	$do_categoria = DB_DataObject::factory('categoria');
	$do_categorias = $do_categoria -> getCategorias();

	$do_tipo = DB_DataObject::factory('tipo');
	$do_tipos = $do_tipo -> getTipos();
	$do_tipos_edit = $do_tipo -> getTipos();

	require_once('public/listado_categorias.html');
	exit;
?>
