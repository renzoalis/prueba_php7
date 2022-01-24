<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	// PEAR
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');
	phpinfo();exit;
		
	$_SESSION['app_id'] = APP_ID;

	if($_POST['config_usuario_id']){
		configurar_user($_POST);
	}
	$activo['Inicio'] = 'active';

	// si es un usuario basic, se o redirecciona a ventas movilkes
	$usr = DB_DataObject::factory('usuario');
	$usr -> usua_id = $_SESSION['usuario']['id'];
	$usr -> find(true);
	$vendedor = $usr -> esUsuarioBasic();

	if($vendedor){
		header("Location: ../ventas_moviles/movil.php");
	}else {
		require_once('../templates/templates/index.html');
	}	

	// $do_caja = DB_DataObject::factory('caja');
	// $respuesta = $do_caja -> getDatosServicio();


 	// $do_proveedor_inicio = DB_DataObject::factory('proveedor_cuenta_corriente');
 	// $listado = $do_proveedor_inicio -> getMontosResumen();
 	// print_r($listado);exit;

	require_once('../templates/templates/index.html');
	exit;
?>
