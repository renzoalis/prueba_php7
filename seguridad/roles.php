<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	
	require_once(CFG_PATH.'/data.config');
	// PEAR
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	require_once('funciones_roles.php');
	// print_r($_POST);
	if($_POST){ //print_r($_POST);
		if($_POST['edit_rol_id']){
			editar_rol($_POST); //funciones_usuarios.php
			unset($_POST);
		} 
		// DB_DataObject::debugLevel(5);
		if($_POST['agregar_rol']){ //exit;
			agregar_rol($_POST); //funciones_usuarios.php
			unset($_POST);
		}
	}

	$do_roles = DB_DataObject::factory('rol');
    $do_roles ->find();

   	$contador_usuarios = array();

    $do_roles_N = DB_DataObject::factory('rol');
    $do_roles_N->find(); 
    while ($do_roles_N -> fetch()){
    	$do_usu_roles = DB_DataObject::factory('usuario_rol');
    	$do_usu_roles -> usrrol_rol_id = $do_roles_N -> rol_id;
    	$do_usu_roles -> find();
    	$contador_usuarios[$do_roles_N -> rol_id] = $do_usu_roles -> N;
    }


	require_once('public/roles.html');
	exit;
?>
