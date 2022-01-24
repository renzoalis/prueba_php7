<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	
	require_once(CFG_PATH.'/data.config');
	// PEAR
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	require_once('funciones_permisos.php');

	// DB_DataObject::debugLevel(5);
	// print_r($_POST); 
	if($_POST){ //print_r($_POST);
		if($_POST['rol_id'] && $_POST['modulo_id']){
			editar_permiso($_POST); //funciones_permisos.php
			unset($_POST);
		} 
	}

	$do_permisos = DB_DataObject::factory('permiso');
	$do_rol = DB_DataObject::factory('rol');
	$do_modulo = DB_DataObject::factory('modulo');
	$do_tipo_acceso = DB_DataObject::factory('tipo_acceso');
    
    $do_permisos ->joinAdd($do_rol);
    $do_permisos ->joinAdd($do_modulo);
    $do_permisos ->joinAdd($do_tipo_acceso);
   
	$do_roles_tabla = DB_DataObject::factory('rol');
	$do_roles_tabla -> whereAdd('rol_baja = 0');
	$do_roles_tabla -> find();

	$do_modulos_tabla = DB_DataObject::factory('modulo');
	$do_modulos_tabla -> whereAdd('mod_baja = 0');
	$do_modulos_tabla -> find();

	while ($do_roles_tabla -> fetch()){
		$array_roles[$do_roles_tabla ->rol_id]['rol_nombre'] = $do_roles_tabla -> rol_nombre;
		$array_roles[$do_roles_tabla ->rol_id]['rol_id'] = $do_roles_tabla -> rol_id;
	}


    $do_permisos ->find();
    while ($do_permisos -> fetch()){
    	$matriz[$do_permisos -> mod_id][$do_permisos -> rol_id] = $do_permisos -> tipoacc_id; 
    }



	require_once('public/permisos.html');
	exit;
?>
