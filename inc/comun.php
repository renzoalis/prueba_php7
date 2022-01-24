<?php
//*****************************************************************************
// Funciones en comun para el sistema
//*****************************************************************************

require_once("DB/DataObject.php");

if($_GET['showDB'])	DB_DataObject::DebugLevel($_GET['showDB']);

function getPermisos(){

	$script_nombre = explode("/", $_SERVER['PHP_SELF']);
	$script_largo = sizeof($script_nombre);

	$script_string = ''.$script_nombre[$script_largo-2].'/'.$script_nombre[$script_largo-1];
	$ses = $_SESSION['usuario'];

	$vista = DB_DataObject::factory('view_usuario_login');
	$vista -> modpag_scriptname = $script_string; 
	$vista -> app_id = $ses['app_id'];
	$vista -> usua_id = $ses['id'];
	$vista -> find(true);

	return $vista -> tipoacc_id;
}

function getColorModulo($modulo_id){
	$do_modulo = DB_DataObject::factory('modulo');
	$do_modulo -> mod_id = $modulo_id;
	$do_modulo -> find(true);

	return $do_modulo -> mod_color;
}

function getModuloABM($app_id, $modulo_id, $usuario_id, $objeto){
	$permisos_para_editar = getPermisos($app_id, $modulo_id, $usuario_id);

	if ($permisos_para_editar >= $objeto -> permiso_editar){
		$puedeEditar = true;
	} else {
		$puedeEditar = false;
	}
	return $puedeEditar;
}

function configurar_user($objeto) {
	$do_usuario_config = DB_DataObject::factory('usuario');
	$do_usuario_config -> usua_id = $objeto['config_usuario_id'];
	$do_usuario_config -> find(true);

	$do_usuario_config -> usua_nombre = $objeto['usuarioNombre_config'];
	if($objeto['usuarioPass_config_1']){
		if($objeto['usuarioPass_config_1'] == $objeto['usuarioPass_config_2'])	{	
			$do_usuario_config -> usua_pwd = md5($objeto['usuarioPass_config_1']); 
		}
	}
	$do_usuario_config -> usua_email = $objeto['usuarioMail_config'];
	$do_usuario_config -> update();
	header('Location: logout.php');

}

?>
