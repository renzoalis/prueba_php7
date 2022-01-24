<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_transp = DB_DataObject::factory('transportista');
	$do_transp -> transportista_baja = 0;
	$do_transp -> orderBy('transportista_nombre ASC');
	$do_transp -> find();

	$transportistas = array();

	while ($do_transp -> fetch()) { 
		$transportistas[$do_transp -> transportista_id]['id'] = $do_transp -> transportista_id;
		$transportistas[$do_transp -> transportista_id]['nombre'] = $do_transp -> transportista_nombre;
	}

	if(!$_GET['fecha_desde']){
		$fecha_actual = new DateTime();	
		$f_desde =  $fecha_actual -> modify("-1 month");
		$campoFecha = date_format($f_desde,'d/m/Y').' - '.date('d/m/Y');
	}

	if($_GET['id_transportista']) {

		$ccte = DB_DataObject::factory('transportista_cuenta_corriente');

		if(!$_GET['fecha_desde']){
			$fecha_actual = new DateTime();	
			$f_desde =  $fecha_actual -> modify("-1 month");
			$dsd = date_format($f_desde,'d/m/Y');
			$hst = date('d/m/Y');
			$cc = $ccte -> transportistaGetCC($_GET['id_transportista'],$dsd,$hst);
			$campoFecha = date_format($f_desde,'d/m/Y').' - '.date('d/m/Y');
		} else {
			$cc = $ccte -> transportistaGetCC($_GET['id_transportista'],$_GET['fecha_desde'],$_GET['fecha_hasta']);
			$campoFecha = $_GET['fecha'];
		}

		$ccte2 = DB_DataObject::factory('transportista_cuenta_corriente');
		$saldo = $ccte2 -> getUltimaCC($_GET['id_transportista']);
		if($saldo -> ccte_saldo_actual < 0) {
			$class_cc = "cc_rojo";
		} else {
			$class_cc = "cc_verde";
		}
	}	


	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////    Servicio para Actualizar la CC de Transportistas en Matriz  /////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $do_transportistas_serviceCC = DB_DataObject::factory('transportista_cuenta_corriente');
	$do_transportistas_serviceCC -> whereAdd('ccte_inf_matriz IS NULL');
	$do_transportistas_serviceCC -> find();
	$objeto['PUESTO_ID'] = PUESTO_ID;
	$i=0;
	while ($do_transportistas_serviceCC -> fetch()) {
		$objeto['CC'][$i]['ccte_id'] = $do_transportistas_serviceCC -> ccte_id;
		$objeto['CC'][$i]['ccte_transportista_id'] = $do_transportistas_serviceCC -> ccte_transportista_id;
		$objeto['CC'][$i]['ccte_fh'] = $do_transportistas_serviceCC -> ccte_fh;
		$objeto['CC'][$i]['ccte_operacion_tipo'] = $do_transportistas_serviceCC -> ccte_operacion_tipo;
		$objeto['CC'][$i]['ccte_operacion_id'] = $do_transportistas_serviceCC -> ccte_operacion_id;
		$objeto['CC'][$i]['ccte_importe'] = $do_transportistas_serviceCC -> ccte_importe;
		$objeto['CC'][$i]['ccte_saldo_actual'] = $do_transportistas_serviceCC -> ccte_saldo_actual;
		$objeto['CC'][$i]['ccte_inf_matriz'] = $do_transportistas_serviceCC -> ccte_inf_matriz;
		if($do_transportistas_serviceCC -> ccte_operacion_tipo == 9){
    		$do_pago_transportista = DB_DataObject::factory('pago_transportista');
    		$do_forma_pago = DB_DataObject::factory('forma_pago');
    		$do_pago_transportista -> joinAdd($do_forma_pago);
    		$do_pago_transportista -> pago_id = $do_transportistas_serviceCC -> ccte_operacion_id;
    		$do_pago_transportista -> find(true);

			$objeto['CC'][$i]['ccte_desc'] = $do_pago_transportista -> fp_desc;
		}elseif($do_transportistas_serviceCC -> ccte_operacion_tipo == 10){
			$objeto['CC'][$i]['ccte_desc'] = "Costo Flete";
		}
		$i++;
	}

	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,SRV_PATH."/transportistas/service_actualizarCCTransp.php");
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($objeto));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $respuesta = curl_exec ($ch);
    $actualizarCC = json_decode($respuesta,true);
  
    foreach ($actualizarCC as $key => $value ) {
		$transportista_cuenta_corriente_actualizar = DB_DataObject::factory('transportista_cuenta_corriente');
		$transportista_cuenta_corriente_actualizar -> ccte_id = $value;
		$transportista_cuenta_corriente_actualizar -> find(true);
		$transportista_cuenta_corriente_actualizar -> ccte_inf_matriz = date('Y-m-d H:i:s');

		$notif_cc = $transportista_cuenta_corriente_actualizar -> update();
    }

    curl_close ($ch);

	require_once('public/cc_transportistas.html');
	exit;
?>
