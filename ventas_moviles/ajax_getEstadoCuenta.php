<?php
 
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

    if($_POST['id'] != 9999){
    	$cc = DB_DataObject::factory('cliente_cuenta_corriente');
        $cc -> ccte_cliente_id = $_POST['id'];
        $cc -> orderBy('ccte_id DESC');
        $cc -> find(true);

        if ($cc -> ccte_saldo_actual < 0) {
        	$data['clase'] = 'cc_rojo';
    		$data['texto'] = 'Con deuda ($ ' . $cc -> ccte_saldo_actual.')';
        } else {
        	$data['clase'] = 'cc_verde';
        	$data['texto'] = 'Sin deuda ($ ' . $cc -> ccte_saldo_actual.')';
        }
    }


	print_r(json_encode($data));

?>