<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_cheques = DB_DataObject::factory('cheque');
	$cheques = $do_cheques -> getCheques();
	$cheques -> orderBy('cheque_id DESC');
	$cheques -> find();

	if($_POST['estado_nuevo']){
		$update_cheque = DB_DataObject::factory('cheque');
		$id_edit = $update_cheque -> actualizarCheque($_POST);
		if($id_edit){
			$cheque_estado = DB_DataObject::factory('cheque_estado');
			$cheque_estado -> vestado_id = $_POST['estado_nuevo'];
			$cheque_estado -> find(true);

			header("Location: cartera.php?id_edit=".$id_edit."&estado=".$cheque_estado -> vestado_descripcion);
		}

	}
	if($_POST['hidden-cobrar']) {
		$cheques_cubiertos = 0;
		if(!empty($_POST['check'])){
			foreach ($_POST['check'] as $k => $v) {
				$objeto['id_cheque'] = $k;
				$objeto['estado_nuevo'] = 2; // Cubierto

				$cheque = DB_DataObject::factory('cheque');
				$id_cheque = $cheque -> actualizarCheque($objeto);
				$cheques_cubiertos ++;
			}
			header("Location: cartera.php?cant_cobrado=".$cheques_cubiertos.'&busqueda='.$_POST['campo_busqueda']);
		}
	}

	$caja = DB_DataObject::factory('caja');
	$cajaAbierta = $caja -> cajaAbiertaHoy();


	require_once('public/listado_cheques.html');
	exit;
?>
