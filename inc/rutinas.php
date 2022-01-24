<?php
	require_once("DB/DataObject.php");

/**
 * Modulo Tipo de Acceso
 *
 * recibe id del tipo de acceso y retorna true si no hay algun permiso que utilice ese tipo de acceso
 *
 * @param integer -> id de tipo de acceso
 * @return boolean -> true en caso de no haber algun permiso que utilice ese tipo de acceso
 */
function validarTipoAcceso ($fields){

	$tipoacc_id = $fields['tipoacc_id'];
	$do_permiso = DB_DataObject::factory('permiso');
	$do_permiso -> permiso_tipoacc_id = $tipoacc_id;
	if ($do_permiso -> find(true)){
		return array('tipoacc_nombre' => 'No se puede eliminar un tipo de acceso que est&eacute; siendo utilizado por un Permiso');
	}
	else{
		return true;
	}
}

/**
 * Modulo Pagina
 *
 * recibe los datos de una pagina y retorna true si no existe
 *
 * @param integer -> datos ingresados de la pagina
 * @return boolean -> true en caso de no estar repetida la pagina
 */
function validarPagina($fields){
    
	$do_mod_pag = DB_DataObject::factory('modulo_paginas');
    $do_mod_pag -> modpag_mod_id = $fields['modpag_mod_id'];
	$do_mod_pag -> modpag_scriptname = $fields['modpag_scriptname'];
	if ($do_mod_pag -> find(true)){
		return array('modpag_scriptname' => 'Ya existe la p&aacute;gina ingresada');
	}
	else{
		return true;
	}
}
?>
