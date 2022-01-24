<?php
 	header('Content-Type: application/json');
	require_once('../config/web.config');
	// require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');
	ob_clean();
	/* */
	function enviarDatos($objeto){
        $ch = curl_init();         
        curl_setopt($ch, CURLOPT_URL,SRV_PATH."/services/getCierreDeCaja.php");
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($objeto));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $remote_server_output = curl_exec ($ch);

        curl_close ($ch);
        return $remote_server_output;   
    }


	$do_caja = DB_DataObject::factory('caja');
	$do_caja -> whereAdd('caja_matriz_fh IS NULL' );
    $do_caja -> orderBy('caja_id DESC');
    $do_caja -> find(true);
	// print_r($do_caja);exit;
	$do_conci = DB_DataObject::factory('conciliacion');
	$do_conci -> c_caja_id = $do_caja -> caja_id;
	$do_conci -> find(true);

	$do_dtvs = DB_DataObject::factory('dtv');

	$venta = DB_DataObject::factory('venta');
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////								//////////////////////////////////////////////////////////
	///////////////////////////////			DATOS				//////////////////////////////////////////////////////////
	///////////////////////////////								//////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$datos['Puesto'] = PUESTO_ID;
	$datos['Caja'] = $do_caja -> getDatosServicio();
	// $datos['Conciliacion'] = $do_conci -> getDatosEnvioServicio();
	$datos['Dtvs'] = $do_dtvs -> getDatosEnvioServicio();
	$datos['Reporte_online'] = $venta -> reporteOnline();
	$data = enviarDatos($datos);
	$respuesta = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data), true );
	// print_r($respuesta);exit;
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////								//////////////////////////////////////////////////////////
	///////////////////////////////			RESPUESTA			//////////////////////////////////////////////////////////
	///////////////////////////////								//////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	if($respuesta){
		// print_r($respuesta);exit;
		$do_caja -> caja_matriz_id = $respuesta['CAJA']['id_caja'];
		$do_caja -> caja_matriz_fh = date('Y-m-d H:i:s'); 
		$do_caja -> update();
		
		$servicio = DB_DataObject::factory('log_servicio');
		$servicio -> log_usua_id = $_SESSION['usuario']['id'];
		$servicio -> log_script = SRV_PATH."/services/getCierreDeCaja.php";
		$servicio -> log_fh = date('Y-m-d H:i:s');
		$servicio -> log_data_server = json_encode($_SERVER);
		$servicio -> log_respuesta = json_encode($respuesta);
		$servicio -> insert();

		
		echo ( $respuesta['CAJA']['id_caja'] ); 
	}

	exit;
?>