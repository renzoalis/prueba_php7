<?php	
	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
    require_once(INC_PATH.'/AccesoIntranet.class.php');	
	// librerias PEAR
	// require_once('HTML/QuickForm.php');
	//DB_DataObject::debugLevel(5);	
	// phpinfo();exit;

	if($_POST){
	if (esUsuario($_POST)) {	

		$do_log_ingreso = DB_DataObject::factory('log_ingreso');
		$do_log_ingreso -> loging_usua_id = $_SESSION['usuario']['id'];
		$do_log_ingreso -> loging_app_id = $_SESSION['usuario']['app_id'];
		$do_log_ingreso -> loging_fecha = date('Y-m-d H:i:s');
		$do_log_ingreso -> insert();

		
		if (isset($_SESSION['pagina_originante']))
			header('Location: '.$_SESSION['pagina_originante']);
	   	else 
	    header('Location: ../'.PGN_INDEX);
	    	exit;
	}
	}
	require_once('../templates/templates/login.html');
	exit;

	function esUsuario($post) {		
		$encontrado = AccesoIntranet::registrarUsuario($post['usuario'],$post['clave'],APP_ID);	
		if ($encontrado === true) {			
			return true;
		}
        elseif ($encontrado == '-1'){
			return array('usuario' => 'Error: Falla en acceso al sistema');
		}
		else {
			return array('usuario' => 'Usuario o clave no v&aacute;lida');
        }
	}
?>
