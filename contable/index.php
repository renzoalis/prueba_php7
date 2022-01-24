<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$noti_cheque = DB_DataObject::factory('cheque');
	$notificacion = $noti_cheque -> getCantACobrar(); 
	$noti_monto_a_cobrar = $noti_cheque -> getCantPlataACobrar();
	
	$noti_cheque = DB_DataObject::factory('cheque_propio');
	$notificacionpropio = $noti_cheque -> getCantAcubrir();
	$noti_monto_a_cubrir = $noti_cheque -> getCantPlataAcubrir();

	require_once('public/index.html');
	exit;
?>
