<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	
	require_once(CFG_PATH.'/data.config');
	// PEAR
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	require_once('funciones_usuarios.php');

	if($_POST){ //print_r($_POST);
		if($_POST['edit_usuario_id']){
			editar_user($_POST); //funciones_usuarios.php
			unset($_POST);
		}
		if($_POST['agregar_usuario']){
			agregar_user($_POST); //funciones_usuarios.php
			unset($_POST);
		}
	}

	$do_usuarios = DB_DataObject::factory('usuario');

    $do_usu_areas = DB_DataObject::factory('usuario_area');
	$do_areas = DB_DataObject::factory('area');
    $do_usu_areas->joinAdd($do_areas);

    $do_usuarios->joinAdd($do_usu_areas);

    $do_usua_roles = DB_DataObject::factory('usuario_rol');
    $do_roles = DB_DataObject::factory('rol');
    $do_usua_roles->joinAdd($do_roles);

    $do_usuarios->joinAdd($do_usua_roles);
    $do_usuarios->find();

    $do_roles_select = DB_DataObject::factory('rol');
    $do_roles_select->find();

    $do_roles_select_add = DB_DataObject::factory('rol');
    $do_roles_select_add->find();

    $do_areas_select = DB_DataObject::factory('area');
    $do_areas_select->find();

    $do_areas_select_add = DB_DataObject::factory('area');
    $do_areas_select_add->find();

	require_once('public/usuarios.html');
	exit;
?>
