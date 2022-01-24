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

	if($_POST['edit_tipo']) {// Editar tipo
		//print_r($_POST);exit;
		$do_tipo = DB_DataObject::factory('tipo');
		$id_tipo_edit = $do_tipo -> modificarTipo($_POST);
		header("Location: tipos.php?id_tipo=".$id_tipo_edit);

	}
	if($_POST['add_tipo']) { // Alta tipo
		//print_r($_POST);exit;
		$do_tipo = DB_DataObject::factory('tipo');
		$id_tipo = $do_tipo -> agregarTipo($_POST);
		header("Location: tipos.php?id_nuevo=".$id_tipo);
	}

	$permiso = getPermisos($_SESSION['app_id'], $_SESSION['modulo_id'], $_SESSION['usuario']['id']);
	$do_tipo = DB_DataObject::factory('tipo');
	$do_tipos = $do_tipo -> getTipos();

	require_once('public/listado_tipos.html');
	exit;
?>
