<?php
	session_name(WWW_DIR.PUESTO_ID);
session_start();
	// session_destroy();	
	require_once('config/web.config');
	
	require_once(CFG_PATH.'/data.config');	
	
	// librerias PEAR
	require_once('HTML/QuickForm.php');

	
	
	// Fue autentificado el usuario ?	
	
	//$_SESSION['user'] = 'borrame'; //borrame
	
	
	if(isset($_SESSION['user'])) header('Location: '.PGN_INDEX);
	$frm = new HTML_QuickForm('login','post',$_SERVER['REQUEST_URI']);	
	// $frm->addElement('header', null, 'INGRESO USUARIOS');
	$frm->addElement('html', 'Acceso al sistema');
	$frm->addElement('text', 'usuario', 'Usuario: ', array('size' => 10, 'maxlength' => 10));
	$frm->addElement('password', 'clave', 'Clave: ', array('size' => 20, 'maxlength' => 255));
	$frm->addElement('submit', 'B1', 'Entrar', array('class' => 'button'));
	$frm->applyFilter('usuario', 'trim');
	
	$frm->addRule('usuario', 'Usuario requerido', 'required', null, 'client');
	$frm->addRule('clave', 'Clave requerida', 'required', null, 'client');
	//$frm->addRule('usuario', 'Ingrese el CUIT en formato 99-99999999-9', 'regex', '/^[0-9][0-9][-][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][-][0-9]$/','client');
	$frm->setRequiredNote(FRM_NOTA);
	$frm->setJsWarnings(FRM_WARNING_TOP, FRM_WARNING_BUTTON);
	$frm->addFormRule('esUsuario');
	//print_r($_POST);
	
	if ($frm->validate()) {	
		if (isset($_SESSION['pagina_originante']))
			header('Location: '.$_SESSION['pagina_originante']);
	   	else 
			header('Location: '.PGN_INDEX);
	    	exit;
	}
	
	$tpl = new tpl();
	$tpl->assign('webTitulo', WEB_TITULO);
	$tpl->assign('secTitulo', 'Ingreso al Sistema');
	$tpl->assign('frm', $frm->toHtml());	
	//$tpl->assign('includeFile', 'login.htm');	
	$tpl->display('index_login.htm');
	//DB_DataObject::debugLevel(5);
	function esUsuario($post) {
		// do
		
		$do_usuario = DB_DataObject::factory('usuario');
		// set
		$do_usuario->usua_pwd = md5($post['clave']);
		$do_usuario->usua_usrid = $post['usuario'];
		
		if( $do_usuario->find(true) > 0) {
			//$do_usuario->getLinks();
			session_name(WWW_DIR.PUESTO_ID);
session_start();
			//$_SESSION['usuario']['rol'] = $do_usuario->_u
			$_SESSION['usuario']['nombre'] = $do_usuario->usua_usrid;
                        $_SESSION['usuario']['nom_ape'] = $do_usuario->usua_nombre;
			$_SESSION['usuario']['permisos'] = $do_usuario->getPermisos();
			$_SESSION['usuario']['id'] = $do_usuario->usua_id;
                        $_SESSION['usuario']['mail'] = $do_usuario->usua_email;
			return true;	
			
		}
		return array('usuario' => 'Usuario o clave no v�lida');
		
	}
?>
