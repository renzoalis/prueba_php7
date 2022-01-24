<?php
	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');
	$do_tipos = DB_DataObject::factory('tipo');
	$tipos = $do_tipos -> tiposConStock();

	if($tipos -> N == 1){
		$tipos -> fetch();
		$respuesta['tipo_id'] = $tipos -> tipo_id;
		$respuesta['tipo_nombre'] = $tipos -> tipo_desc;
		$respuesta['unSoloTipo'] = 1;

		
		print_r(json_encode($respuesta));
		exit;
	}else{
		print_r(json_encode(false));
		exit;
	}
?>