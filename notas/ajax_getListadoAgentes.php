<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$id = $_POST['id'];

	$notas_agentes = DB_DataObject::factory('notas_tipo_agente');
	$notas_agentes -> whereAdd('ta_id = '.$id);
	$notas_agentes -> find(true);

	$a = DB_DataObject::factory($notas_agentes -> ta_tabla);
	switch ($id) {
		
		case 1: // Cliente
			$agentes = $a -> getClientes();
			while ($agentes -> fetch()) {
				if($agentes -> cliente_id != 9999){
					$listado[$agentes -> cliente_id] = $agentes -> cliente_nombre; 
				}
			}
			break;

		case 2: // Proveedor
			$agentes = $a -> getProveedores();
			while ($agentes -> fetch()) {
				$listado[$agentes -> prov_id] = $agentes -> prov_nombre; 
			}
			break;

		case 3: // Despachante
			$agentes = $a -> getDespachantes();
			while ($agentes -> fetch()) {
				$listado[$agentes -> despachante_id] = $agentes -> despachante_nombre; 
			}
			break;

		case 4: // Importador
			$agentes = $a -> getImportadores();
			while ($agentes -> fetch()) {
				$listado[$agentes -> importador_id] = $agentes -> importador_nombre; 
			}
			break;

		case 5: // Exportador
			$agentes = $a -> getExportadores();
			while ($agentes -> fetch()) {
				$listado[$agentes -> exportador_id] = $agentes -> exportador_nombre; 
			}
			break;

		case 6: // Transportista
			$agentes = $a -> getTransportistas();
			while ($agentes -> fetch()) {
				$listado[$agentes -> transportista_id] = $agentes -> transportista_nombre; 
			}
			break;
	}

	require_once('public/ajax_selectAgentes.html');
	exit;
?>