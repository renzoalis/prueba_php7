<?php
	require_once('../config/web.config');
	require_once(INC_PATH.'/AccesoIntranet.class.php');	
	
	AccesoIntranet::finalizarSesion();
	header('Location:../'.PGN_LOGIN);
	exit;
?>