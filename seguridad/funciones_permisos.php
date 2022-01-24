<?php

function editar_permiso($objeto) { //print_r($objeto);exit;
	$do_permiso_edit = DB_DataObject::factory('permiso');
	$do_permiso_edit -> permiso_rol_id = $objeto['rol_id'];
	$do_permiso_edit -> permiso_mod_id = $objeto['modulo_id'];
	
	if($do_permiso_edit -> find(true)){
		$do_permiso_edit -> permiso_tipoacc_id = $objeto['tipoacc_id'];
		$do_permiso_edit -> update();
	} else {
		$do_permiso_add = DB_DataObject::factory('permiso');
		$do_permiso_add -> permiso_rol_id = $objeto['rol_id'];
		$do_permiso_add -> permiso_mod_id = $objeto['modulo_id'];
		$do_permiso_add -> permiso_tipoacc_id = $objeto['tipoacc_id'];
		$do_permiso_add -> insert();
	}

}


?>