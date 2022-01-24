<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_categorias = DB_DataObject::factory('categoria');
	$do_categorias -> cat_nombre = $_POST['nombre'];

	if($do_categorias -> find(true)){
		$respuesta = $do_categorias -> cat_id;
	}else{
		$respuesta = false;
	}
	//print_r($_POST);
	echo(json_encode($respuesta));
?>