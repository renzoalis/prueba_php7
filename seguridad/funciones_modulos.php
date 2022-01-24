<?php
// DB_DataObject::debugLevel(1);
function agregar_modulo($objeto) {
	$do_modulo_add = DB_DataObject::factory('modulo');

	$do_modulo_add -> mod_nombre = $objeto['moduloNombre'];
	$do_modulo_add -> mod_app_id = 5;
	$do_modulo_add -> mod_baja = $objeto['moduloEstado'];
	$do_modulo_add -> insert();
}

function editar_modulo($objeto) { //print_r($objeto);exit;
	$do_modulo_edit = DB_DataObject::factory('modulo');
	$do_modulo_edit -> mod_id = $objeto['edit_modulo_id'];
	$do_modulo_edit -> find(true);

	$do_modulo_edit -> mod_nombre = $objeto['moduloNombre'];
	$do_modulo_edit -> mod_baja = $objeto['moduloEstado'];
	$do_modulo_edit -> update();

	if($objeto['agregar_respuesta']){
		$do_modulo_pagina_add = DB_DataObject::factory('modulo_paginas');
		$do_modulo_pagina_add -> modpag_mod_id = $objeto['edit_modulo_id'];
		$do_modulo_pagina_add -> modpag_scriptname = $objeto['agregar_respuesta'];
		$do_modulo_pagina_add -> insert();
	}
}

function eliminar_pagina($objeto) { //print_r($objeto);exit;
	$query_delete = "DELETE FROM modulo_paginas WHERE modpag_id =".$objeto['id_pagina_delete'];
	$do_mod_pag_delete = DB_DataObject::factory('modulo_paginas');
	$do_mod_pag_delete -> query($query_delete);
}

?>