<?php

function agregar_user($objeto) {
	$do_usuario_add = DB_DataObject::factory('usuario');

	$do_usuario_add -> usua_nombre = $objeto['usuarioNombre'];
	$do_usuario_add -> usua_usrid = $objeto['usuarioID'];
	$do_usuario_add -> usua_pwd = md5($objeto['usuarioPass']);
	$do_usuario_add -> usua_email = $objeto['usuarioMail'];
	$do_usuario_add -> usua_baja = $objeto['usuarioEstado'];
	$id_usuario_add = $do_usuario_add -> insert();

	$do_usu_area_add = DB_DataObject::factory('usuario_area');
	$do_usu_area_add -> usarea_usua_id = $id_usuario_add;
	$do_usu_area_add -> usarea_area_id = $objeto['usuarioArea'];
	$do_usu_area_add -> insert();

	$do_usua_rol_add = DB_DataObject::factory('usuario_rol');
	$do_usua_rol_add -> usrrol_usua_id = $id_usuario_add;
	$do_usua_rol_add -> usrrol_rol_id = $objeto['usuarioRol'];
	$do_usua_rol_add -> usrrol_app_id = 5;
	$do_usua_rol_add -> insert();
}

function editar_user($objeto) {
	$do_usuarios_edit = DB_DataObject::factory('usuario');
	$do_usuarios_edit -> usua_id = $objeto['edit_usuario_id'];
	$do_usuarios_edit -> find(true);
	// print_r($do_usuarios_edit);exit;

	$do_usuarios_edit -> usua_nombre = $objeto['usuarioNombre'];
	$do_usuarios_edit -> usua_usrid = $objeto['usuarioID'];
	if($objeto['usuarioPass']){	$do_usuarios_edit -> usua_pwd = md5($objeto['usuarioPass']); }
	$do_usuarios_edit -> usua_email = $objeto['usuarioMail'];
	$do_usuarios_edit -> usua_baja = $objeto['usuarioEstado'];
	$do_usuarios_edit -> update();

	$do_usu_areas_edit = DB_DataObject::factory('usuario_area');
	$do_usu_areas_edit -> usarea_usua_id = $objeto['edit_usuario_id'];
	$do_usu_areas_edit -> find(true);
	$do_usu_areas_edit -> usarea_area_id = $objeto['usuarioArea'];
	$do_usu_areas_edit -> update();

	$do_usua_roles_edit = DB_DataObject::factory('usuario_rol');
	$do_usua_roles_edit -> usrrol_usua_id = $objeto['edit_usuario_id'];
	$do_usua_roles_edit -> find(true);
	$do_usua_roles_edit -> usrrol_rol_id = $objeto['usuarioRol'];
	$do_usua_roles_edit -> update();
}


?>