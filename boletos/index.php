<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	// if($_POST['rechazar_solicitud']) {
	// 	$sol = DB_DataObject::factory('_solicitud');
	// 	$sol -> s_id = $_POST['edit_solicitud'];
	// 	$sol -> find(true);
	// 	$sol -> s_estado = 2;
	// 	$sol -> update();
	// 	header("Location: index.php?id_sol_elim=".$$_POST['rechazar_solicitud'].'&busqueda='.$_POST['campo_busqueda']);
	// }

	// if($_POST['confirmar_solicitud']) {
	// 	$sol = DB_DataObject::factory('_solicitud');
	// 	$sol -> s_id = $_POST['edit_solicitud'];
	// 	$sol -> find(true);
	// 	$sol -> s_estado = 3;
	// 	$sol -> update();
	// 	header("Location: index.php?id_sol_confirm=".$$_POST['confirmar_solicitud'].'&busqueda='.$_POST['campo_busqueda']);
	// }

	$boletos = DB_DataObject::factory('boleto');
	
	$boletos -> getBoletos($_GET['filtro_estado']);

	require_once('public/listado.html');
	exit;
?>
