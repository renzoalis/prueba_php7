<?php
/**
 * Table Definition for transferencias
 */
require_once 'DB/DataObject.php';

class DataObjects_Transferencias extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'transferencias';      // table name
    public $transf_id;                       // int(11)  not_null primary_key auto_increment group_by
    public $transf_fh;                       // datetime(19)  not_null
    public $transf_tipo;                     // int(2)  group_by
    public $transf_origen;                   // int(11)  group_by
    public $transf_destino;                  // int(11)  group_by
    public $transf_transp;                   // int(11)  group_by
    public $transf_numero_viaje;             // varchar(256)  
    public $transf_cant;                     // int(11)  group_by
    public $transf_obs;                      // varchar(256)  
    public $transf_costo_carga;              // float(11)  group_by
    public $transf_costo_descarga;           // float(11)  group_by
    public $transf_estado;                   // int(1)  group_by
    public $transf_matriz_id;                // int(11)  not_null group_by
    public $transf_transp_origen;            // varchar(256)  
    public $transf_costo_flete;              // float(11)  group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Transferencias',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

// Trae tabla Transferencias recibidas
    function getTransferencias($id=false){

        $te_estado = DB_DataObject::factory('transferencia_estado');
        $this -> joinAdd($te_estado);
        $this -> whereAdd('transf_tipo = 2 and transf_estado IN(2,3)'); 

        if($id){
            $this -> transf_id = $id;
            $this -> find(true);
        } else {
            $this -> orderBy('transf_id DESC');
            $this -> find();
        }

        return $this;
    }

// Trae tabla Transferencias enviadas
    function getTransferenciasEnviadas($id=false){

        $te_estado = DB_DataObject::factory('transferencia_estado');
        $this -> joinAdd($te_estado);
        $this -> whereAdd('transf_tipo = 1 and transf_estado IN(2,3)');
        
        if($id){
            $this -> transf_id = $id;
            $this -> find(true);
        } else {
            $this -> orderBy('transf_id DESC');
            $this -> find();
        }

        return $this;
    }

// Trae tabla Transferencias archivadas
    function getTransferenciasArchivadas($id=false){

        $te_estado = DB_DataObject::factory('transferencia_estado');
        $this -> joinAdd($te_estado);
        $this -> whereAdd('transf_estado IN(4,5,6)');         //Archivada
        
        if($id){
            $this -> transf_id = $id;
            $this -> find(true);
        } else {
            $this -> orderBy('transf_id DESC');
            $this -> find();
        }

        return $this;
    }

// Trae nombre del puesto
    function getPuestoNombre($id=false){

       $puesto = DB_DataObject::factory('puesto');
       $puesto -> puesto_id = $id;
       $puesto -> find(true); 
       // si no lo encuentra en la lista de puestos, quiere decir que es el puesto actual
       if ($puesto -> puesto_nombre) {
        $puesto_nombre = $puesto -> puesto_nombre;
       } else {
        $puesto_nombre = PUESTO_NOMBRE;
       } 
        return $puesto_nombre;
    }

// Trae nombre del puesto
    function getTranspNombre($id=false){

    if($id){
        $transp = DB_DataObject::factory('transportista');
        $transp -> transportista_id = $id;
        $transp -> find(true); 
        $transp_nombre = $transp -> transportista_nombre;
    }else{
        $transp_nombre = "-";
    }
    

    return $transp_nombre;
    }

// Servicio para enviar nuevas transferencias al sistema matriz
    function enviarTransferencia($objeto){
        $ch = curl_init();         
        curl_setopt($ch, CURLOPT_URL,SRV_PATH."/services/cargarTransferencia.php");
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($objeto));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $remote_server_output = curl_exec ($ch);
        curl_close ($ch);
        return $remote_server_output;   
    }


// Alta de nueva tranferencia
    function agregarTransferencia($objeto){
        //print_r($objeto);exit;
        $transferencias = DB_DataObject::factory('transferencias');
        $transferencias -> transf_fh = date('Y-m-d H:i:s');
        $transferencias -> transf_tipo = 1;
        $transferencias -> transf_estado = 1;   //Pendiente de envio
        $transferencias -> transf_obs = $objeto['input_observacion_transferencia'];
        $transferencias -> transf_destino =  $objeto['combo_puestos'];
        $transferencias -> transf_transp =  $objeto['combo_transp'];
        $transferencias -> transf_numero_viaje =  $objeto['numero_viaje'];
        $transferencias -> transf_origen =  PUESTO_ID;
        $transferencias -> transf_costo_carga =  $objeto['costo_final_total'];
        $transferencias -> transf_cant = $objeto['cant_prod'];
        $id_transferencia = $transferencias -> insert();

        $objeto['transf_origen'] = PUESTO_ID;
        $objeto['transf_puesto_id'] = $id_transferencia; // Id de la transf en el puesto
        $objeto['transf_destino'] = $objeto['combo_puestos'];

        $transportista = DB_DataObject::factory('transportista');
        $transportista -> transportista_id = $objeto['combo_transp'];
        $transportista -> find(true);

        $objeto['transf_transp_origen'] = $transportista -> transportista_nombre;
        $objeto['transf_numero_viaje'] = $objeto['numero_viaje'];

        //Tengo que restar stock
        $objeto['total_bultos'] = 0;
        $i=0;
        foreach ($objeto['prod'] as $p) {
            $productos = DB_DataObject::factory('producto');
            $productos -> prod_id = $p['id'];
            $productos -> find(true); 
            if($productos -> getStockXLote($p['lote']) > $objeto['input_cantidad']){           // hay stock
                $lote = $productos -> restarStockXLote($p['cantidad'],$p['lote']);
                if($lote['id']){
                    $detalle = DB_DataObject::factory('transferencias_detalle');
                    $detalle -> detalle_transferencia_id = $id_transferencia;
                    $detalle -> detalle_producto_id = $p['id'];
                    $detalle -> detalle_calibre = $p['calibre'];
                    $detalle -> detalle_producto_cantidad_origen = $p['cantidad'];
                    $detalle -> detalle_costo_carga = $p['precio_carga_unitaria'];
                    // $detalle -> detalle_costo_flete_origen = $p['precio_flete_unitario'];

                    //Calculo totales de CARGA y FLETE
                    $total_carga += $p['precio_carga_unitaria']*$p['cantidad'];
                    $total_flete += $p['precio_flete_unitario']*$p['cantidad'];

                    //El costo unitario del lote es costo u (incluye flete y descarga de la compra) + costo carga transf + costo flete transf 

                    // $detalle -> detalle_costo_unitario = $lote['costou'] + $p['precio_carga_unitaria'] + $p['precio_flete_unitario'];
                    $detalle -> detalle_costo_unitario = $lote['costou'];

                    $objeto['prod'][$i]['costou'] = $detalle -> detalle_costo_unitario;

                    $detalle -> detalle_lote = $lote['id'];

                    if($lote['lote_desc']){
                        $descripcion_lote = $lote['lote_desc']." -> ".PUESTO_CODIGO."00".$lote['id'];
                        $detalle -> detalle_lote_desc = $descripcion_lote;
                    }else{
                        $detalle -> detalle_lote_desc = PUESTO_CODIGO."00".$lote['id'];
                    }

                    $objeto['prod'][$i]['lote_desc'] = $detalle -> detalle_lote_desc;
                    $det_id = $detalle -> insert();

                    $transf_detalle_stock = DB_DataObject::factory('transferencias_detalle_stock');
                    $transf_detalle_stock -> tds_detalle_id = $det_id; 
                    $transf_detalle_stock -> tds_prodstock_id = $lote['id'];
                    $transf_detalle_stock -> tds_prod_cant = $p['cantidad'];
                    $transf_detalle_stock -> insert();

                    $objeto['total_bultos']  = $objeto['total_bultos'] + $p['cantidad'];
                }else{
                    return 'error-stock'; // No hay stock suficiente.
                }   
            }else{
                return 'error-stock'; // No hay stock suficiente.
            }
            $i++;
        }

        $objeto['total_carga'] = $total_carga;
        $objeto['total_flete'] = $total_flete;

        // print_r($objeto);exit;

        //Cargo los parametros para insertar la carga
        $params['input_id_transp'] = $transportista -> transportista_id;
        $params['transf_id'] = $id_transferencia;
        $params['input_monto'] =  $objeto['total_carga'];  
        $params['input_obs_concepto'] = "Costo de carga en Transferencia T:".$id_transferencia; 
        $carga = DB_DataObject::factory('carga');
        $id_carga = $carga -> nuevaCarga($params);
    
       //CARGO LOS PARAMETROS PARA INSERTAR EL FLETE
        // $params_flete['transf_id'] = $id_transferencia;
        // $params_flete['input_id_transp'] = $transportista -> transportista_id;
        // $params_flete['input_obs_concepto'] = "Costo de Flete en Transferencia T".$id_transferencia;
        // $params_flete['input_monto_total'] = $objeto['total_flete'];
        // $params_flete['input_comprob'] = $objeto['input_comprob'];

        // //$params['compra_detalle_id'] = $objeto['concepto_compra_detalle_id'];
        // $flete = DB_DataObject::factory('flete');
        // $id_flete = $flete -> nuevoFlete($params_flete);

        foreach ($objeto['prod'] as $p) {
            // $detalle_flete = DB_DataObject::factory('flete_detalle');
            // $detalle_flete -> detalle_flete_id = $id_flete;
            // $detalle_flete -> detalle_prod_id = $p['id'];
            // $detalle_flete -> detalle_prod_cant = $p['cantidad'];
            // $detalle_flete -> detalle_prod_costo_u = $p['precio_flete_unitario'];
            // $detalle_flete -> detalle_prod_calibre = $p['calibre'];
            // $detalle_flete -> detalle_ps_id = $p['lote'];
            // $detalle_flete -> insert();

            $detalle_carga = DB_DataObject::factory('carga_detalle');
            $detalle_carga -> detalle_carga_id = $id_carga;
            $detalle_carga -> detalle_carga_prod = $p['detalle_prod'];
            $detalle_carga -> detalle_carga_costo = $p['precio_carga_unitaria'];
            $detalle_carga -> detalle_carga_cant = $p['cantidad'];
            $detalle_carga -> detalle_ps_id = $p['lote'];
            $detalle_carga -> insert();
        }




        $id_transf_matriz = $transferencias -> enviarTransferencia($objeto);

        if($id_transf_matriz){ // El sistema matriz recibio la transferencia
        	$transferencias -> transf_estado = 2;
            $transferencias -> transf_matriz_id = (int) $id_transf_matriz;
        	$update = $transferencias -> update();
            
            $resp['id_matriz'] = $id_transf_matriz;
        } else { 
            $resp['id_matriz'] = 'error-conexion'; 
        }
        
        $resp['id_local'] = $id_transferencia;
        
        return $resp;
    }

    // Funcion nueva para cargar tranferencias desde el servicio
    function cargarTransferenciasService($objeto){
    // print_r($objeto);exit;
        $cantidad=1;
        $actualizar = array();
        foreach ($objeto['transferencias'] as $key => $transf) {

            $this -> transf_matriz_id = $transf['transf_matriz_id'];
            $this -> transf_fh = date('Y-m-d H:i:s');
            $this -> transf_origen =  $transf['transf_origen'];
            $this -> transf_numero_viaje =  $transf['transf_numero_viaje'];
            $this -> transf_destino =  PUESTO_ID;
            $this -> transf_tipo =  2; // INGRESO DE MERCADERIA : Recibida desde matriz
            $this -> transf_costo_carga =  $transf['transf_costo_total_carga'];
            $this -> transf_cant = $transf['transf_cant'];
            $this -> transf_obs = $transf['transf_obs'];
            $this -> transf_transp_origen = $transf['transf_transp_origen'];
            $this -> transf_estado = 3;     //Recibida por puesto destino

            $id_transferencia = $this -> insert();
            if($id_transferencia){                      //Se inserto la transferencia
                $actualizar[$cantidad] = $transf['transf_matriz_id'];           
            }

            foreach ($transf['productos'] as $p) {
                        $detalle = DB_DataObject::factory('transferencias_detalle');
                        $detalle -> detalle_transferencia_id = $id_transferencia;
                        $detalle -> detalle_producto_id = $p['detalle_prod_id'];
                        $detalle -> detalle_calibre = $p['detalle_calibre'];
                        $detalle -> detalle_costo_unitario = $p['detalle_costou'];
                        $detalle -> detalle_producto_cantidad_origen = $p['detalle_prod_cant_origen'];
                        $detalle -> detalle_costo_carga = $p['detalle_costo_carga'];
                        // $detalle -> detalle_costo_flete_origen = $p['detalle_costo_flete_origen'];
                        $detalle -> detalle_lote = $p['detalle_lote'];   //El lote queda temporal y el real lo cargo cuando acepto la transferencia y tengo id de lote
                        $detalle -> detalle_lote_desc = $p['detalle_lote_desc'];        //Detalle del lote que viene desde puesto origen
                        $detalle -> insert();
            }
            $cantidad++;

        }
        $actualizar['cantidad'] = $cantidad;

        return $actualizar;
    }

    // Guardar transferencia recibida y sumar stock
    function recibiTransferencia($objeto){
        //Suma stock y actualizar detalle de la transferencia con stockrecibido
        $diferencia_stock = 0;
        //print_r($objeto);exit;
        //DB_DataObject::debugLevel(1);
        foreach ($objeto['prod'] as $p) {
            //print_r($p['lote_desc']);exit;
            $do_producto = DB_DataObject::factory('producto');

            $costo_unitario = $p['costo_unitario'] + $p['costo_descarga_u'];
            $id = $do_producto -> sumarStockTransferencia($objeto['transferencia_id'],$p['producto_id'],$p['calibre'],$p['cantidad_real'],$costo_unitario,$p['lote_desc']);

            $do_detalle = DB_DataObject::factory('transferencias_detalle');
            //$do_detalle -> whereAdd('detalle_transferencia_id ='.$objeto['transferencia_id'].' AND detalle_producto_id = '.$p['producto_id'].' AND detalle_calibre = "'.$p['calibre'].'"');
            $do_detalle -> whereAdd('detalle_transferencia_id ='.$objeto['transferencia_id'].' AND detalle_producto_id = '.$p['producto_id'].' AND detalle_calibre = "'.$p['calibre'].'" AND detalle_lote = '.$p['lote'].'');

            $do_detalle -> find(true);
            $do_detalle -> detalle_producto_cantidad_destino = $p['cantidad_real'];
            $do_detalle -> detalle_costo_descarga = $p['costo_descarga_u'];
            $do_detalle -> detalle_costo_flete_destino = $p['costo_flete_destino_u'];
            $do_detalle -> detalle_lote = $id;

            $total_descarga += $p['costo_descarga_u']*$p['cantidad_real'];
            $total_flete += $p['costo_flete_destino_u']*$p['cantidad_real'];

            $do_detalle -> update();
            $lotes_producto[$p['lote']]['lote_destino'] = $id;
            $diferencia_stock = $diferencia_stock + ($p['cantidad_origen']-$p['cantidad_real']);

        }

        $objeto['diferencia_stock'] = $diferencia_stock;
        $objeto['total_descarga'] = $total_descarga;
        $objeto['total_flete'] = $total_flete;


        if($diferencia_stock > 0){
            $objeto['trasnf_estado'] = 4;   //ESTADO EN MATRIZ: DIFERENCIA DE STOCK
        }else{
            $objeto['trasnf_estado'] = 2;   //ACEPTADA
        }
       
        //Actualizo la tabla de transferencias
        $do_transferencias = DB_DataObject::factory('transferencias');
            
        //Llamo al servicio para actualizar el sistema matriz
        $recibo_matriz = json_decode($do_transferencias -> enviarReciboTransferencia($objeto),true);
        if($recibo_matriz['estado'] == 200){
            $do_transferencias -> whereAdd('transf_id ='.$objeto['transferencia_id']);
            $do_transferencias -> find(true);
            if($diferencia_stock > 0){
                $do_transferencias -> transf_estado = 5; //Aceptada con diferencia de stock
            }else{
                $do_transferencias -> transf_estado = 4; //Aceptada
            }
            $do_transferencias -> transf_costo_descarga = $total_descarga;
            $do_transferencias -> transf_costo_flete = $total_flete;
            $do_transferencias -> transf_transp = $objeto['transf_transp'];;
            $do_transferencias -> update();


            //CARGO LOS PARAMETROS PARA INSERTAR LA DESCARGA
            $params['transf_id'] = $objeto['transferencia_id'];
            $params['input_monto'] = $objeto['total_descarga'];
            $params['input_obs_concepto'] = "Costo de Descarga en Transf Nro".$do_transferencias -> transf_id;
            $params['input_comprob'] = "";
            $params['input_obs_devolver'] = "";
            
            $descarga = DB_DataObject::factory('descarga');
            $id_desc = $descarga -> nuevaDescarga($params);


            //CARGO LOS PARAMETROS PARA INSERTAR EL FLETE
            $params['transf_id'] = $objeto['transferencia_id'];
            $params['input_monto'] = $objeto['total_flete'];
            $params['input_obs_concepto'] = "Costo de Flete en Transf Nro".$do_transferencias -> transf_id;
            $params['input_comprob'] = "";
            $params['input_obs_devolver'] = "";
            $params['input_id_transp'] = $objeto['input_id_transp'];
            
            $flete = DB_DataObject::factory('flete');
            $id_flete = $flete -> nuevoFlete($params);

            foreach ($objeto['prod'] as $p) {
                //print_r($p);exit;
                $detalle_descarga = DB_DataObject::factory('descarga_detalle');
                $detalle_descarga -> detalle_descarga_id = $id_desc;
                $detalle_descarga -> detalle_descarga_prod = $p['detalle_prod'];
                $detalle_descarga -> detalle_descarga_costo = $p['costo_descarga_u'];
                $detalle_descarga -> detalle_descarga_cant = $p['cantidad_real'];
                $detalle_descarga -> detalle_ps_id = $lotes_producto[$p['lote']]['lote_destino'];
                $detalle_descarga -> insert();

                $detalle_flete = DB_DataObject::factory('flete_detalle');
                $detalle_flete -> detalle_flete_id = $id_flete;
                $detalle_flete -> detalle_prod_id = $p['detalle_prod'];
                $detalle_flete -> detalle_prod_costo_u = $p['costo_flete_destino_u'];
                $detalle_flete -> detalle_prod_cant = $p['cantidad_real'];
                $detalle_flete -> detalle_ps_id = $lotes_producto[$p['lote']]['lote_destino'];
                $detalle_flete -> detalle_prod_calibre = $p['calibre'];
                $detalle_flete -> insert();
            }

        }

        return $recibo_matriz;        
    }

    function enviarReciboTransferencia($objeto){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,SRV_PATH."/services/reciboTransferencia.php");
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($objeto));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $remote_server_output = curl_exec ($ch);
        curl_close ($ch);
         
        // hacemos lo que queramos con los datos recibidos
        return $remote_server_output;   
    }


    function getCantBultos() {
       
        $detalle = DB_DataObject::factory('transferencias_detalle');
        $detalle -> detalle_transferencia_id =$this -> transf_id;
        $detalle -> find();

       while ($detalle -> fetch()) {
          $cantBultos = $cantBultos + $detalle -> detalle_producto_cantidad_origen;
        }
        
        return $cantBultos;
    }

    function getEstado() {
       
        $te_estado = DB_DataObject::factory('transferencia_estado');
        $te_estado -> te_id = $this -> transf_estado;
        $te_estado -> find(true);

        return $te_estado;
    }

    function getIngresosCaja($fhInicio,$fhCierre=false) {
        /* Enviadas: costo de carga
           Recibidas: costo de descarga */

        if(!$fhCierre){
            $fhCierre = date('Y-m-d H:i:s');
        }

        $this -> whereAdd('transf_fh BETWEEN "'.$fhInicio.'" AND  "'.$fhCierre.'"');
        $this -> find();

        $suma = 0;
        $total['Carga'] = 0;
        $total['Descarga'] = 0;

        while($this -> fetch()){
            if($this -> transf_tipo == 1) { // Enviada
                $total['Carga'] += $this -> transf_costo_carga;
                $suma += $this ->  transf_costo_carga;
            } elseif ($this -> transf_tipo == 2) { // Recibida
                $total['Descarga'] += $this -> transf_costo_descarga;
                $suma += $this ->  transf_costo_descarga;
            }
        }

        $total['Total'] = $suma;

        return $total;
    }

    function diferenciaMercaderia($objeto){
       
        
        //Sumo la cantidad de mercaderia devuelta, para que la proxima no exceda el total vendido
        $do_transferencias = DB_DataObject::factory('transferencias_detalle');
        $do_transferencias -> detalle_id = $objeto['concepto_transf_detalle_id'];
        $do_transferencias -> find(true);

        $do_transferencias -> detalle_mercaderia_diferencia = $do_transferencias -> detalle_mercaderia_diferencia + $objeto['input_cantidad_dev'];
        $do_transferencias -> update();

        // print_r($do_transferencias);exit;
        //ACA TENGO QUE CREAR EL OBJETO DIFERENCIA MERCADERIA Y CARGAR EL MOVIMIENTO
        $do_diferencia_mercaderia = DB_DataObject::factory('diferencia_mercaderia');
        $do_diferencia_mercaderia -> dif_fh = date('Y-m-d H:i:s');
        $do_diferencia_mercaderia -> dif_usua_id = $_SESSION['usuario']['id'];
        $do_diferencia_mercaderia -> dif_lote = $do_transferencias -> detalle_lote;
        $do_diferencia_mercaderia -> dif_transferencia_id = $do_transferencias -> detalle_transferencia_id;
        $do_diferencia_mercaderia -> dif_detalle_id = $do_transferencias -> detalle_id;
        // $do_diferencia_mercaderia -> dif_cantidad = $do_transferencias -> detalle_producto_cantidad_origen - $do_transferencias -> detalle_producto_cantidad_destino;
        $do_diferencia_mercaderia -> dif_cantidad = $objeto['input_cantidad_dev'];
        $do_diferencia_mercaderia -> dif_restauro_stock = $objeto['input_restaurar_stock'];
        $do_diferencia_mercaderia -> dif_prod_desc = $objeto['input_obs_devolver'];
        $do_diferencia_mercaderia -> dif_obs = $objeto['input_obs_concepto'];

        $do_diferencia_mercaderia -> insert();


        if($objeto['input_restaurar_stock'] == 1){ //Restauramos STOCK
            $do_producto_stock = DB_DataObject::factory('producto_stock');
            $do_producto_stock -> whereAdd('ps_id = '.$do_transferencias -> detalle_lote);
            $do_producto_stock -> find(true);

            $do_producto_stock -> ps_cantidad = $do_producto_stock -> ps_cantidad + $objeto['input_cantidad_dev'];
            $do_producto_stock -> ps_precio_prom_venta_parcial = 0; //Pongo en 0 el ppv parcial para recalcularlo
            $do_producto_stock -> ps_precio_prom_venta = 0; //como hubo una dev de mercaderia, pongo en 0 el ppv, para que se realice el calculo de nuevo

            $do_producto_stock -> update();
        }else{
            $do_perdida_mercaderia = DB_DataObject::factory('perdida_mercaderia');
            $do_perdida_mercaderia -> perdida_ps_id = $do_transferencias -> detalle_lote;
            $do_perdida_mercaderia -> perdida_fh = date('Y-m-d H:i:s');
            $do_perdida_mercaderia -> perdida_usua_id = $_SESSION['usuario']['id'];
            $do_perdida_mercaderia -> perdida_desc = "Perdida de mercaderia en transferencia: ".$do_transferencias -> detalle_transferencia_id;
            $do_perdida_mercaderia -> perdida_cantidad = $objeto['input_cantidad_dev'];
            $do_perdida_mercaderia -> insert();
        }
 
         $id_dm = $this -> insert();
 
         $param['input_monto'] = $objeto['input_monto']; 
         $param['nota_fh'] = date('Y-m-d H:i:s');
         $param['id_dm'] = $id_dm;
 
         $do_nota = DB_DataObject::factory('notas');
         $NC = $do_nota -> notaProvDesdeCompra($param);
         
         return $id_dm;
    }

    function getCantidadSinCostos($fecha_desde,$fecha_hasta){
        if(!$fecha_hasta){
            $fecha_hasta = date('Y-m-d H:i:s');
        }
        $do_transferencias = DB_DataObject::factory('transferencias');
        $do_transferencias_detalle = DB_DataObject::factory('transferencias_detalle');
        $do_transferencias -> joinAdd($do_transferencias_detalle);
        //compra_concepto_fletes IS NULL OR
        $do_transferencias -> whereAdd('(transf_fh BETWEEN "'.$fecha_desde.'" AND "'.$fecha_hasta.'") AND ( transf_estado IN(3,5))');
        $do_transferencias -> find();

        return $do_transferencias -> N;
    }

}
