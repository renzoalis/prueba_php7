<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	
	require_once(CFG_PATH.'/data.config');
	// PEAR
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	require_once('funciones_modulos.php');
	// print_r($_POST);
	if($_POST){ //print_r($_POST);exit;
		if($_POST['edit_modulo_id']){
			editar_modulo($_POST); //funciones_usuarios.php
			unset($_POST);
		} 
		// DB_DataObject::debugLevel(5);
		if($_POST['agregar_modulo']){ //exit;
			agregar_modulo($_POST); //funciones_usuarios.php
			unset($_POST);
		}
		if($_POST['id_pagina_delete']){ //exit;
			eliminar_pagina($_POST); //funciones_usuarios.php
			unset($_POST);
		}
	}

	$do_modulos = DB_DataObject::factory('modulo');
    $do_modulos ->find();
 //    while ($do_modulos -> fetch()){
 //    	print_r($do_modulos);
	// }		exit;

   	$contador_paginas = array();

    $do_modulos_N = DB_DataObject::factory('modulo');
    $do_modulos_N->find(); 
    while ($do_modulos_N -> fetch()){
    	$do_modulos_paginas = DB_DataObject::factory('modulo_paginas');
    	$do_modulos_paginas -> modpag_mod_id = $do_modulos_N -> mod_id;
    	$do_modulos_paginas -> find();
    	$contador_paginas[$do_modulos_N -> mod_id] = $do_modulos_paginas -> N;
    }

    $array_paginas = array();

    $do_modulos_select = DB_DataObject::factory('modulo');
    $do_modulos_select->find(); 
    while ($do_modulos_select -> fetch()){
    	$do_mod_pag = DB_DataObject::factory('modulo_paginas');
    	$do_mod_pag -> modpag_mod_id = $do_modulos_select -> mod_id;
    	$do_mod_pag -> find();
    	while ($do_mod_pag -> fetch()){
    		$array_paginas[$do_modulos_select -> mod_id][$do_mod_pag -> modpag_id] = $do_mod_pag -> modpag_scriptname;
    	}
    }
    $paginas_modulos = json_encode($array_paginas);

    // print_r($paginas_modulos);

	require_once('public/modulos.html');
	exit;
?>
