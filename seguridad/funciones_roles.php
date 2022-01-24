<?php

function agregar_rol($objeto) {
	$do_rol_add = DB_DataObject::factory('rol');

	$do_rol_add -> rol_nombre = $objeto['rolNombre'];
	$do_rol_add -> rol_baja = $objeto['rolEstado'];
	$do_rol_add -> insert();
}

function editar_rol($objeto) { //print_r($objeto);exit;
	$do_rol_edit = DB_DataObject::factory('rol');
	$do_rol_edit -> rol_id = $objeto['edit_rol_id'];
	$do_rol_edit -> find(true);

	$do_rol_edit -> rol_nombre = $objeto['rolNombre'];
	$do_rol_edit -> rol_baja = $objeto['rolEstado'];
	$do_rol_edit -> update();
}


?>