<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	
	require_once(CFG_PATH.'/data.config');
	// PEAR
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	require_once('funciones_areas.php');
	// print_r($_POST);
	if($_POST){ //print_r($_POST);
		if($_POST['edit_area_id']){
			editar_area($_POST); //funciones_usuarios.php
			unset($_POST);
		} 
		// DB_DataObject::debugLevel(5);
		if($_POST['agregar_area']){ //exit;
			agregar_area($_POST); //funciones_usuarios.php
			unset($_POST);
		}
	}

	$do_areas = DB_DataObject::factory('area');
    $do_areas ->find();

   	$contador_usuarios = array();

    $do_areas_N = DB_DataObject::factory('area');
    $do_areas_N->find(); 
    while ($do_areas_N -> fetch()){
    	$do_usu_areas = DB_DataObject::factory('usuario_area');
    	$do_usu_areas -> usarea_area_id = $do_areas_N -> area_id;
    	$do_usu_areas -> find();
    	$contador_usuarios[$do_areas_N -> area_id] = $do_usu_areas -> N;
    }


	require_once('public/areas.html');
	exit;
?>
