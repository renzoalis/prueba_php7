<?php

function agregar_area($objeto) {
	$do_area_add = DB_DataObject::factory('area');

	$do_area_add -> area_nombre = $objeto['areaNombre'];
	$do_area_add -> area_baja = $objeto['areaEstado'];
	$do_area_add -> insert();
}

function editar_area($objeto) { //print_r($objeto);exit;
	$do_area_edit = DB_DataObject::factory('area');
	$do_area_edit -> area_id = $objeto['edit_area_id'];
	$do_area_edit -> find(true);

	$do_area_edit -> area_nombre = $objeto['areaNombre'];
	$do_area_edit -> area_baja = $objeto['areaEstado'];
	$do_area_edit -> update();
}


?>