<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$prem = $_POST['premium'];

	$do_categorias = DB_DataObject::factory('categoria');
	$do_categorias -> cat_id = $_POST['id'];
	$do_categorias -> find(true);

	$do_tipos = DB_DataObject::factory('tipo');
	//$do_tipos -> tipo_id = $do_categorias -> cat_tipo_id;
	$do_tipos -> find();


	require_once('public/modales/edit_categoria.html');
	exit;
?>