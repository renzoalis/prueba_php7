<?php
 
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_modulos = DB_DataObject::factory('modulo');
	$do_modulos -> mod_id = $_POST['id'];
	$do_modulos -> find(true);
	
	$do_paginas = DB_DataObject::factory('modulo_paginas');
	$do_paginas -> modpag_mod_id = $_POST['id']; 
	$do_paginas -> find();

	$objeto = array();
	$objeto['modulo'] = $do_modulos; 

	while ($do_paginas -> fetch()) {
		$objeto['paginas'][$do_paginas -> modpag_id]['id'] = $do_paginas -> modpag_id;
		$objeto['paginas'][$do_paginas -> modpag_id]['script'] = $do_paginas -> modpag_scriptname;
	}

	print_r(json_encode($objeto));

?>