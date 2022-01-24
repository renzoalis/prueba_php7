<?php
	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	// PEAR
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');
    
    session_name(WWW_DIR.PUESTO_ID);
session_start();
	if(!isset($_SESSION['usuario'])) {
		session_destroy();
		session_name(WWW_DIR.PUESTO_ID);
session_start();			
		$_SESSION['pagina_originante'] = $_SERVER['REQUEST_URI'];
		header('Location: ../'.PGN_LOGIN);
		exit;
	}	
    //$acceso_mensaje = "No tiene permiso para acceder a ".$_SESSION['no_autorizado'];

    require_once('../templates/templates/denegado.html');
    exit;