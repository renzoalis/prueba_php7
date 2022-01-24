<?php
/**
 * Table Definition for caja
 */
require_once 'DB/DataObject.php';

class DataObjects_Caja extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'caja';                // table name
    public $caja_id;                         // int(11)  not_null primary_key auto_increment group_by
    public $caja_fh_inicio;                  // datetime(19)  not_null
    public $caja_fh_cierre;                  // datetime(19)  
    public $caja_monto_inicio;               // float(9)  not_null group_by
    public $caja_monto_cierre;               // float(9)  group_by
    public $caja_usua_inicio;                // int(11)  not_null group_by
    public $caja_usua_cierre;                // int(11)  group_by
    public $caja_estado;                     // int(1)  not_null group_by
    public $caja_cobros_ft;                  // float(11)  group_by
    public $caja_pagos_ft;                   // float(11)  group_by
    public $caja_gastos_ft;                  // float(11)  group_by
    public $caja_cant_ventas;                // int(11)  group_by
    public $caja_total_ventas;               // float(11)  group_by
    public $caja_cant_cambios;               // int(11)  group_by
    public $caja_cant_articulos;             // int(11)  group_by
    public $caja_boletos_inicio;             // float(11)  group_by
    public $caja_boletos_fin;                // float(11)  group_by
    public $caja_cheques_inicio;             // float(11)  group_by
    public $caja_cheques_fin;                // float(11)  group_by
    public $caja_conciliacion_id;            // int(11)  group_by
    public $caja_matriz_id;                  // int(11)  group_by
    public $caja_matriz_fh;                  // datetime(19)  

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Caja',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    // Retorna true si la caja se abrio hoy. 
    // Sirve para validar que no se puedan cargar ventas, pagos, cobros o gastos sin la caja abierta.
    function cajaAbiertaHoy(){
        // $this -> whereAdd('caja_fh_inicio LIKE "%'.date('Y-m-d').'%"'); // Está comentado porque al ser el mercado, por ahi abren la caja a las 5am.
        $this -> orderBy('caja_id DESC');
        $this -> find(true);

        if($this -> caja_estado == 1) { // Abierta
            $resp = true;
        } elseif($this -> caja_estado == 2) { // Cerrada
            $resp = false;
        }

        return $resp;
    }

    function getUltimaCaja(){

        $this -> orderBy('caja_id DESC');
        $this -> find(true);

        return $this;
    }

    function getEstado(){
        $caja_estado = DB_DataObject::factory('caja_estado');
        $caja_estado -> ce_id = $this -> caja_estado;

        $caja_estado -> find(true);

        return $caja_estado -> ce_nombre;
    }

    function abrirCaja($objeto){
        $this -> caja_fh_inicio = date('Y-m-d H:i:s');
        $this -> caja_usua_inicio = $_SESSION['usuario']['id'];
        $this -> caja_monto_inicio = $objeto['monto_efectivo'];
        $this -> caja_cheques_inicio = $objeto['monto_cheque'];
        $this -> caja_estado = 1;

        $id = $this -> insert();

        return $id;
    }

    function cerrarCaja($objeto){
        // Datos de la caja
        //print_r($objeto);exit;
            $this -> caja_id = $objeto['caja_id'];
            $this -> find(true);

        // Concilio el efectivo real final
            if($objeto['caja_monto_real'] < $objeto['caja_monto_cierre']) { // Hay menos plata de la que debería.
                // Creamos un gasto de conciliacion.
                $gasto = DB_DataObject::factory('gasto');

                $gasto -> gasto_fh = date('Y-m-d H:i:s');
                $gasto -> gasto_monto_total = $objeto['caja_monto_cierre'] - $objeto['caja_monto_real'];
                $gasto -> gasto_usuario_id = $_SESSION['usuario']['id'];
                $gasto -> gasto_concepto = 'Conciliación de caja';
                $gasto -> gasto_observacion = 'Diferencia en cierre de caja';
                $gasto -> gasto_categoria = 9;

                $id_gasto = $gasto -> insert();
            }
            
            if($objeto['caja_monto_real'] > $objeto['caja_monto_cierre']) { // Hay más plata de la que debería.
                // A nada, creamos un cobro anónimo.

                $cobro_cliente = DB_DataObject::factory('cobro_cliente');

                $o['input_id_cliente'] = 9999;
                $o['combo_fpago'] = 1;
                $o['input_obs_pago'] = 'Conciliación de caja';
                $o['input_monto_contado'] = $objeto['caja_monto_real'] - $objeto['caja_monto_cierre'];

                $id_cobro = $cobro_cliente -> nuevoCobro($o);
            }

        // Actualizo los datos de la caja
        $this -> caja_fh_cierre = date('Y-m-d H:i:s');
        $this -> caja_usua_cierre = $_SESSION['usuario']['id'];
        $this -> caja_monto_cierre = $objeto['caja_monto_real'];
        $this -> caja_cheques_fin = $objeto['caja_monto_cheque'];
        $this -> caja_estado = 2;
        $this -> update();

        // Cargo los datos estadísticos para enviar el informe
        foreach ($objeto['parametros'] as $key => $value) {
            $cd = DB_DataObject::factory('caja_detalle');
            $cd -> detalle_caja_id = $this -> caja_id;
            $cd -> detalle_tipo_id = $key;
            $cd -> detalle_valor = $value;
            $cd -> insert();
        }

        return $this -> caja_id;
    }   

    function getNuevosDatosCaja($inicio,$final=false) {

        if(!$inicio){
            $inicio = $this -> caja_fh_inicio;
        }
        if(!$final){
            $final = date('Y-m-d H:i:s');
        }
        //Inicio de caja
       
        $respuesta['inicio'] = $this -> caja_monto_inicio + $this -> caja_cheques_inicio;


        //Total de ventas (pendientes,saldadas y despachadas)

        $do_venta = DB_DataObject::factory('venta');

        $respuesta['Total_ventas'] = $do_venta -> getMontoTotalVentas($inicio,$final);

        //COBROS A CUENTA CORRIENTE

        $do_cobro_cliente = DB_DataObject::factory('cobro_cliente');
        $respuesta['Cobros_ccte'] = $do_cobro_cliente -> getMontoCobros($inicio,$final) + $do_venta -> getVentasCobroParcial($inicio,$final);
        //VENTAS A CUENTA CORRIENTE
        
        $do_venta = DB_DataObject::factory('venta');
        $respuesta['Total_ventas_a_cc'] = $do_venta -> getMontoVentasACC($inicio,$final);


        //SALIDAS DE CAJA
        $do_salidas_de_caja = DB_DataObject::factory('view_salidas_de_caja');
        $respuesta['Salidas_de_caja'] = $do_salidas_de_caja -> getMontoSalidasDeCaja($inicio,$final);


        //INGRESO POR OTROS
        $do_ingreso_otros = DB_DataObject::factory('view_pagos_otros');
        $respuesta['Ingreso_otros'] = $do_ingreso_otros -> getIngresoOtros($inicio,$final);

        //INGRESO POR OTROS
        $do_ingreso_bancos = DB_DataObject::factory('view_ingresos_banco');
        $respuesta['Ingreso_bancos'] = $do_ingreso_bancos -> getIngresoBanco($inicio,$final);
        
        //NOTAS
        $do_notas = DB_DataObject::factory('notas');
        $respuesta['Notas'] = $do_notas -> getMontoNotasClientes($inicio,$final);
        $respuesta['TOTAL'] = $respuesta['inicio'] + $respuesta['Total_ventas'] + $respuesta['Cobros_ccte'] + $respuesta['Ingreso_otros'] + $respuesta['Ingreso_bancos'] - $respuesta['Total_ventas_a_cc']  - $respuesta['Salidas_de_caja'];

        // print_R($respuesta);exit;
        return $respuesta;
 
    }

    function getDatosCaja($inicio,$final=false) {

        if(!$final){
            $final = date('Y-m-d H:i:s');
        }

        /*
        > suman:
            - cobros clientes

        > restan:
            - Proveedores
            - Transportistas
            - Despachantes
            - Importadores
            - Exportadores
            - Empleados
            - Gastos
            - Costos de transferencia
            - Intereses/multas de cheques/boletos
            - VER Conceptos de ventas
            - VER otros conceptos?
        */

        // Primero traigo todos los datos crudos.

            $cobro_cliente = DB_DataObject::factory('cobro_cliente');
            $pagos['Clientes'] = $cobro_cliente -> getIngresosCaja($inicio,$final);

            $pago_proveedor = DB_DataObject::factory('pago_proveedor');
            $pagos['Proveedores'] = $pago_proveedor -> getIngresosCaja($inicio,$final);

            $pago_transportista = DB_DataObject::factory('pago_transportista');
            $pagos['Transportistas'] = $pago_transportista -> getIngresosCaja($inicio,$final);

            $pago_despachante = DB_DataObject::factory('pago_despachante');
            $pagos['Despachantes'] = $pago_despachante -> getIngresosCaja($inicio,$final);


            $pago_cardesc = DB_DataObject::factory('pago_cardesc');
            $pagos['Cardesc'] = $pago_cardesc -> getIngresosCaja($inicio,$final);

            $pago_importador = DB_DataObject::factory('pago_importador');
            $pagos['Importadores'] = $pago_importador -> getIngresosCaja($inicio,$final);

            $pago_exportador = DB_DataObject::factory('pago_exportador');
            $pagos['Exportadores'] = $pago_exportador -> getIngresosCaja($inicio,$final);

            $pago_empleado = DB_DataObject::factory('pago_empleado');
            $pagos['Empleados'] = $pago_empleado -> getIngresosCaja($inicio,$final);

            $gasto = DB_DataObject::factory('gasto');
            $pagos['Gastos'] = $gasto -> getIngresosCaja($inicio,$final);

            $transferencias = DB_DataObject::factory('transferencias');
            $pagos['Transferencias'] = $transferencias -> getIngresosCaja($inicio,$final);

            $boleto = DB_DataObject::factory('boleto');
            $pagos['Boletos'] = $boleto -> getIngresosCaja($inicio,$final);

            $cheque = DB_DataObject::factory('cheque');
            $pagos['Cheques'] = $cheque -> getIngresosCaja($inicio,$final);

            $cheque_propio = DB_DataObject::factory('cheque_propio');
            $pagos['ChequesPropios'] = $cheque_propio -> getIngresosCaja($inicio,$final);

            $notas = DB_DataObject::factory('notas');
            $pagos['Notas'] = $notas -> getIngresosCaja($inicio,$final);
            
            //INGRESO POR OTROS
            $do_ingreso_otros = DB_DataObject::factory('view_pagos_otros');
            $pagos['Ingreso_otros'] = $do_ingreso_otros -> getIngresosCaja($inicio,$final);


             // print_r($pagos['Cardesc']);exit;

            //print_r($pagos);exit;
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //////////////////////////////////                      EN CAJA                                               /////////////////////////////////////
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            $pagos['En Caja']['Efectivo']['Entradas'] = 0 + $pagos['Clientes']['Efectivo'];
            $pagos['En Caja']['Efectivo']['Salidas'] = 0 + $pagos['Proveedores']['Efectivo'] 
                                                        + $pagos['Transportistas']['Efectivo'] 
                                                        + $pagos['Despachantes']['Efectivo'] 
                                                        + $pagos['Importadores']['Efectivo'] 
                                                        + $pagos['Exportadores']['Efectivo'] 
                                                        + $pagos['Empleados']['Efectivo'] 
                                                        + $pagos['Gastos']['Efectivo'] 
                                                        + $pagos['Cardesc']['Carga']['Efectivo'] 
                                                        + $pagos['Cardesc']['Descarga']['Efectivo'];


            $pagos['En Caja']['Cheques']['Entradas'] = 0 + $pagos['Clientes']['Cheque Terceros'];
            $pagos['En Caja']['Cheques']['Salidas'] = 0 + $pagos['Cheques']['Total'];
            // print_r($pagos);exit;

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //////////////////////////////////                         MOVIMIENTOS EN EFECTIVO                          ///////////////////////////////////////
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


            $pagos['Mov_efect']['Cobros'] = 0 + $pagos['Clientes']['Efectivo'];
            // Gastos
            $pagos['Mov_efect']['Gastos'] = 0 + $pagos['Gastos']['Efectivo'];
            // Pagos
            $pagos['Mov_efect']['Pagos'] = 0 + $pagos['Proveedores']['Efectivo'] 
                                             + $pagos['Transportistas']['Efectivo'] 
                                             + $pagos['Despachantes']['Efectivo'] 
                                             + $pagos['Importadores']['Efectivo'] 
                                             + $pagos['Exportadores']['Efectivo'];
            // Entregas
            $pagos['Mov_efect']['Entregas'] = 0 + $pagos['Empleados']['Efectivo'];
            // Total
            $pagos['Mov_efect']['Total'] = $pagos['Mov_efect']['Cobros'] - $pagos['Mov_efect']['Gastos'] - $pagos['Mov_efect']['Pagos'] - $pagos['Mov_efect']['Entregas'];
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //////////////////////////////////                                                                           //////////////////////////////////////
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //////////////////////////////////                         MOVIMIENTOS DIARIOS                              ///////////////////////////////////////
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


            // EFECTIVO
            $pagos['Movimientos']['Efectivo']['Egresos'] = 0 + $pagos['Proveedores']['Efectivo'] 
                                                             + $pagos['Transportistas']['Efectivo'] 
                                                             + $pagos['Despachantes']['Efectivo'] 
                                                             + $pagos['Importadores']['Efectivo'] 
                                                             + $pagos['Exportadores']['Efectivo'] 
                                                             + $pagos['Empleados']['Efectivo'] 
                                                             + $pagos['Gastos']['Efectivo'] 
                                                             + $pagos['Cardesc']['Carga']['Efectivo'] 
                                                             + $pagos['Cardesc']['Descarga']['Efectivo'];

            $pagos['Movimientos']['Efectivo']['Ingresos'] = 0 + $pagos['Clientes']['Efectivo'];


            $pagos['Movimientos']['Boletos']['Ingresos'] = 0 + $pagos['Clientes']['Boleto'];

            $pagos['Movimientos']['Cheques']['Ingresos'] = 0 + $pagos['Clientes']['Cheque Terceros'];

            $pagos['Movimientos']['Cheques']['Egresos'] = 0 + $pagos['Proveedores']['Cheque Terceros'] 
                                                            + $pagos['Transportistas']['Cheque Terceros']
                                                            + $pagos['Despachantes']['Cheque Terceros'] 
                                                            + $pagos['Importadores']['Cheque Terceros'] 
                                                            + $pagos['Exportadores']['Cheque Terceros'] 
                                                            + $pagos['Cheques']['Rechazados'] 
                                                            + $pagos['Cheques']['Vencidos']
                                                            + $pagos['Cheques']['Salidas_de_caja'] 
                                                            + $pagos['Cardesc']['Carga']['Cheque Terceros'] 
                                                            + $pagos['Cardesc']['Descarga']['Cheque Terceros'];
            $pagos['Movimientos']['ChequesPropios']['Egresos'] = 0 + $pagos['Proveedores']['Cheque Propio'] 
                                                                   + $pagos['Transportistas']['Cheque Propio'] 
                                                                   + $pagos['Despachantes']['Cheque Propio'] 
                                                                   + $pagos['Importadores']['Cheque Propio'] 
                                                                   + $pagos['Exportadores']['Cheque Propio'] 
                                                                   + $pagos['Cardesc']['Carga']['Cheque Propio'] 
                                                                   + $pagos['Cardesc']['Descarga']['Cheque Propio'];

            $pagos['Movimientos']['Depositos']['Egresos'] = 0 + $pagos['Proveedores']['Deposito'] 
                                                              + $pagos['Transportistas']['Deposito'] 
                                                              + $pagos['Despachantes']['Deposito'] 
                                                              + $pagos['Importadores']['Deposito'] 
                                                              + $pagos['Exportadores']['Deposito'] 
                                                              + $pagos['Clientes']['Deposito']
                                                              + $pagos['Cardesc']['Carga']['Deposito'] 
                                                              + $pagos['Cardesc']['Descarga']['Deposito'];

            // print_R($pagos['Movimientos']['Depositos']['Egresos']);exit;

            $pagos['Movimientos']['Depositos']['Ingresos'] = 0 + $pagos['Clientes']['Deposito'] 
                                                               + $pagos['Proveedores']['Deposito'] 
                                                               + $pagos['Transportistas']['Deposito'] 
                                                               + $pagos['Despachantes']['Deposito'] 
                                                               + $pagos['Importadores']['Deposito'] 
                                                               + $pagos['Exportadores']['Deposito'] 
                                                               + $pagos['Cardesc']['Carga']['Deposito'] 
                                                               + $pagos['Cardesc']['Descarga']['Deposito']; 
                                                               // EN LOS INGRESOS TENGO EN CUENTA LOS PAGOS REALIZADOS TMB, PARA QUE LA CUENTA DE BCO QUEDE EN 0
            // ---------

            //COMO INGRESA SE VA A BANCO -> INGRESO = EGRESO
            $pagos['Movimientos']['Tarjeta_debito']['Egresos'] = 0 + $pagos['Clientes']['Tarjeta debito'];
            $pagos['Movimientos']['Tarjeta_debito']['Ingresos'] = 0 + $pagos['Clientes']['Tarjeta debito'];
            $pagos['Movimientos']['Tarjeta_debito']['Total'] = $pagos['Movimientos']['Tarjeta_debito']['Ingresos'] - $pagos['Movimientos']['Tarjeta_debito']['Egresos'];
                


            //COMO INGRESA SE VA A BANCO -> INGRESO = EGRESO
            $pagos['Movimientos']['Tarjeta_credito']['Egresos'] = 0 + $pagos['Clientes']['Tarjeta credito'];
            $pagos['Movimientos']['Tarjeta_credito']['Ingresos'] = 0 + $pagos['Clientes']['Tarjeta credito'];
            $pagos['Movimientos']['Tarjeta_credito']['Total'] = $pagos['Movimientos']['Tarjeta_credito']['Ingresos'] - $pagos['Movimientos']['Tarjeta_credito']['Egresos'];
            // ---------

            // TRANSFERENCIAS
            $pagos['Movimientos']['Transferencias']['Egresos'] = 0 + $pagos['Proveedores']['Transferencia'] 
                                                                   + $pagos['Transportistas']['Transferencia'] 
                                                                   + $pagos['Despachantes']['Transferencia'] 
                                                                   + $pagos['Importadores']['Transferencia'] 
                                                                   + $pagos['Exportadores']['Transferencia'] 
                                                                   + $pagos['Clientes']['Transferencia']
                                                                   + $pagos['Cardesc']['Carga']['Transferencia'] 
                                                                   + $pagos['Cardesc']['Descarga']['Transferencia']; 

            $pagos['Movimientos']['Transferencias']['Ingresos'] = 0 + $pagos['Clientes']['Transferencia'] 
                                                                    + $pagos['Proveedores']['Transferencia'] 
                                                                    + $pagos['Transportistas']['Transferencia'] 
                                                                    + $pagos['Despachantes']['Transferencia'] 
                                                                    + $pagos['Importadores']['Transferencia'] 
                                                                    + $pagos['Exportadores']['Transferencia']
                                                                    + $pagos['Cardesc']['Carga']['Transferencia'] 
                                                                    + $pagos['Cardesc']['Descarga']['Transferencia']; 
            // ---------


             $pagos['Movimientos']['Ingreso_otros']['Ingresos'] = 0 + $pagos['Ingreso_otros']['Con dinero de otro puesto'];
             $pagos['Movimientos']['Ingreso_otros']['Egresos'] = 0 + $pagos['Ingreso_otros']['Con dinero de otro puesto'];
             $pagos['Movimientos']['Ingreso_otros']['Total'] = $pagos['Movimientos']['Ingreso_otros']['Ingresos'] - $pagos['Movimientos']['Ingreso_otros']['Egresos'];


            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //////////////////////////////////                                                                           //////////////////////////////////////
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


        return $pagos;
    }


    function getDatosEnvioServicio(){
        $resp['Info']['puesto'] = PUESTO_ID;
        $resp['Info']['caja_id'] = $this -> caja_id;
        $resp['Info']['caja_fh_inicio'] = $this -> caja_fh_inicio;
        $resp['Info']['caja_fh_cierre'] = $this -> caja_fh_cierre;
        $resp['Info']['caja_monto_inicio'] = $this -> caja_monto_inicio;
        $resp['Info']['caja_monto_cierre'] = $this -> caja_monto_cierre;
        $resp['Info']['caja_usua_inicio'] = $this -> caja_usua_inicio;
        $resp['Info']['caja_usua_cierre'] = $this -> caja_usua_cierre;
        $resp['Info']['caja_conciliacion_id'] = $this -> caja_conciliacion_id;

        $cd = DB_DataObject::factory('caja_detalle');
        $cd -> detalle_caja_id = $this -> caja_id;
        $cd -> find();

        while ($cd -> fetch()) {
            $resp['Detalle'][$cd -> detalle_tipo_id] = $cd -> detalle_valor;
        }

        return $resp;
    }

    function getDatosServicio(){
    
        $do_caja = DB_DataObject::factory('caja');
        // $do_caja -> whereAdd('');
        $do_caja -> orderBy('caja_id DESC');
        $do_caja -> find(true);


        $f_desde = $do_caja -> caja_fh_inicio;
        if($do_caja -> caja_fh_cierre){
            $f_hasta = $do_caja -> caja_fh_cierre;
        }else{
            $f_hasta = date('Y-m-d H:i:s');
        }


        $respuesta['INFO']['caja_fh_inicio'] = $do_caja -> caja_fh_inicio;
        $respuesta['INFO']['caja_fh_cierre'] = $do_caja -> caja_fh_cierre;
        $respuesta['INFO']['caja_monto_inicio'] = $do_caja -> caja_monto_inicio;
        $respuesta['INFO']['caja_monto_cierre'] = $do_caja -> caja_monto_cierre;
        $respuesta['INFO']['caja_usua_inicio'] = $do_caja -> caja_usua_inicio;
        $respuesta['INFO']['caja_usua_cierre'] = $do_caja -> caja_usua_cierre;
        $respuesta['INFO']['puesto'] = PUESTO_ID;
        $respuesta['INFO']['caja_id'] = $do_caja -> caja_id;
        $respuesta['INFO']['caja_conciliacion_id'] = $do_caja -> caja_conciliacion_id;


        $do_venta = DB_DataObject::factory('venta');
        $ventas = $do_venta -> getBultosDiariosCaja($f_desde,$f_hasta);

        $respuesta['INFO']['DETALLE']['VENTAS'] = $ventas['Ventas'];
        $respuesta['INFO']['DETALLE']['BULTOS_VENDIDOS'] = $ventas['Bultos_vendidos'];
        $respuesta['INFO']['DETALLE']['MG_BRUTO_X_BULTO'] = "";

        $resp = $do_caja -> getNuevosDatosCaja($f_desde,$f_hasta);

        $respuesta['CAJA']['INICIO'] = $do_caja -> caja_monto_inicio;
        $respuesta['CAJA']['INGRESOXOTROS'] = $resp['Ingreso_otros'];
        $respuesta['CAJA']['INGRESOXBANCOS'] = $resp['Ingreso_bancos'];
        $respuesta['CAJA']['TOTAL_VENTAS'] = $resp['Total_ventas'];
        $respuesta['CAJA']['COBROS_A_CC'] =  $resp['Cobros_ccte'];
        $respuesta['CAJA']['VENTAS_A_CC'] = $resp['Total_ventas_a_cc'];
        $respuesta['CAJA']['SALIDAS_DE_CAJA'] = $resp['Salidas_de_caja'];
        $respuesta['CAJA']['TOTAL'] = $resp['TOTAL'];
        $respuesta['CAJA']['NOTAS'] = $resp['Notas'];


        $do_cardesc = DB_DataObject::factory('cardesc_cuenta_corriente');
        $respuesta['CUENTAS_CORRIENTES'] = $do_cardesc -> getMontosResumen($f_desde,$f_hasta);

        $do_transportista = DB_DataObject::factory('transportista_cuenta_corriente');
        $respuesta['CUENTAS_CORRIENTES'] += $do_transportista -> getMontosResumen($f_desde,$f_hasta);

        $do_proveedor = DB_DataObject::factory('proveedor_cuenta_corriente');
        $respuesta['CUENTAS_CORRIENTES'] += $do_proveedor -> getMontosResumen($f_desde,$f_hasta);

        //LISTADO CLIENTES
        $do_clientes_deuda = DB_DataObject::factory('view_cliente_con_deuda');
        $do_clientes_deuda -> find();
        // print_r($do_clientes_deuda);exit;
        $i=0;
        while($do_clientes_deuda -> fetch()){
            $i++;
            $respuesta['CLIENTES_CON_DEUDA'][$i]['NOMBRE'] = $do_clientes_deuda -> cliente_nombre;
            $respuesta['CLIENTES_CON_DEUDA'][$i]['DEUDA'] = $do_clientes_deuda -> ccte_saldo_actual;
            $respuesta['CLIENTES_CON_DEUDA'][$i]['DIA'] = $do_clientes_deuda -> getDia($f_desde,$f_hasta);
            $respuesta['CLIENTES_CON_DEUDA'][$i]['ULTIMA'] = $do_clientes_deuda -> getUltimo();
            
        }

          //LISTADO CLIENTES
        $do_proveedores_deuda = DB_DataObject::factory('view_proveedor_con_deuda');
        $do_proveedores_deuda -> find();
        $i=0;
        while($do_proveedores_deuda -> fetch()){
            $i++;
            $respuesta['PROVEEDORES_CON_DEUDA'][$i]['NOMBRE'] = $do_proveedores_deuda -> prov_nombre;
            $respuesta['PROVEEDORES_CON_DEUDA'][$i]['DEUDA'] = $do_proveedores_deuda -> ccte_saldo_actual;
            $respuesta['PROVEEDORES_CON_DEUDA'][$i]['DIA'] = $do_proveedores_deuda -> getDia($f_desde,$f_hasta);
            $respuesta['PROVEEDORES_CON_DEUDA'][$i]['ULTIMA'] = $do_proveedores_deuda -> getUltimo();
            
        }

        //STOCK AGREGADO
        $do_productos = DB_DataObject::factory('producto');
        $respuesta['STOCK_AGREGADO'] = $do_productos -> stockAgregado($f_desde,$f_hasta);

        //STOCK SIN VENTA
        $do_productos_sin_venta = DB_DataObject::factory('producto');
        $respuesta['STOCK_SIN_VENTA'] = $do_productos_sin_venta -> stockSinVenta($f_desde,$f_hasta);

        //SALIDAS DE CAJA
        $do_salidas_de_caja = DB_DataObject::factory('view_salidas_de_caja');
        $do_salidas_de_caja -> whereAdd('fecha between "'.$f_desde.'" and "'.$f_hasta.'"');
        
        $do_salidas_de_caja -> find();

        $i=0;
        while($do_salidas_de_caja -> fetch()){
            $i++;
            $respuesta['SALIDAS_DE_CAJA'][$i]['CATEGORIA'] = $do_salidas_de_caja -> categoria; 
            $respuesta['SALIDAS_DE_CAJA'][$i]['CONCEPTO'] = $do_salidas_de_caja -> concepto; 
            $respuesta['SALIDAS_DE_CAJA'][$i]['FECHA'] = $do_salidas_de_caja -> fecha; 
            $respuesta['SALIDAS_DE_CAJA'][$i]['NOMBRE'] = $do_salidas_de_caja -> nombre; 
            $respuesta['SALIDAS_DE_CAJA'][$i]['MONTO'] = $do_salidas_de_caja -> monto; 
            $respuesta['SALIDAS_DE_CAJA'][$i]['OBSERVACION'] = $do_salidas_de_caja -> observacion; 
            $respuesta['SALIDAS_DE_CAJA'][$i]['ID'] = $do_salidas_de_caja -> id; 
        }


        //SALIDAS POR OTROS
        $do_pagos_otros = DB_DataObject::factory('view_pagos_otros');
        if($f_desde){
            $do_pagos_otros -> whereAdd('FECHA between "'.$f_desde.'" and "'.$f_hasta.'"');
        }
        $do_pagos_otros -> find();

        $i=0;
        while($do_pagos_otros -> fetch()){
            $i++;
            $respuesta['SALIDAS_OTROS'][$i]['FECHA'] = $do_pagos_otros -> FECHA; 
            $respuesta['SALIDAS_OTROS'][$i]['ENTIDAD'] = $do_pagos_otros -> ENTIDAD; 
            $respuesta['SALIDAS_OTROS'][$i]['MONTO'] = $do_pagos_otros -> MONTO; 
            $respuesta['SALIDAS_OTROS'][$i]['OBSERVACION'] = $do_pagos_otros -> OBS; 
            $respuesta['SALIDAS_OTROS'][$i]['ID'] = $do_pagos_otros -> ID; 
        }

        //PRODUCTOS TRANSFERIDOS

        $do_transferencias = DB_DataObject::factory('transferencias');
        $do_transferencia_detalle = DB_DataObject::factory('transferencias_detalle');
        $do_producto = DB_DataObject::factory('producto');
        $do_tipo = DB_DataObject::factory('tipo');
        $do_categoria = DB_DataObject::factory('categoria');

        $do_categoria -> joinAdd($do_tipo);
        $do_producto -> joinAdd($do_categoria);
        $do_transferencia_detalle -> joinAdd($do_transferencias);
        $do_transferencia_detalle -> joinAdd($do_producto);

        $do_transferencia_detalle -> whereAdd('transf_fh between "'.$f_desde.'" and "'.$f_hasta.'"');
        $do_transferencia_detalle -> find();

        $i=0;
        while($do_transferencia_detalle -> fetch()){
            $respuesta['PRODUCTOS_TRANSFERIDOS'][$i]['LOTE'] = $do_transferencia_detalle-> detalle_lote; 
            $respuesta['PRODUCTOS_TRANSFERIDOS'][$i]['PRODUCTO_ID'] = $do_transferencia_detalle -> prod_id;
            $respuesta['PRODUCTOS_TRANSFERIDOS'][$i]['PRODUCTO'] = $do_transferencia_detalle -> tipo_nombre.' | '.$do_transferencia_detalle -> cat_nombre.' | '.$do_transferencia_detalle -> prod_nombre.' ('.$do_transferencia_detalle -> prod_alias.') | '. $do_transferencia_detalle -> detalle_calibre;
            $respuesta['PRODUCTOS_TRANSFERIDOS'][$i]['CANTIDAD'] = $do_transferencia_detalle -> detalle_producto_cantidad_origen; 
            $respuesta['PRODUCTOS_TRANSFERIDOS'][$i]['COSTO_CARGA'] = $do_transferencia_detalle-> detalle_costo_carga; 
            $respuesta['PRODUCTOS_TRANSFERIDOS'][$i]['COSTO_FLETE'] = $do_transferencia_detalle-> detalle_costo_flete_origen; 
            $respuesta['PRODUCTOS_TRANSFERIDOS'][$i]['COSTO_U'] =    $do_transferencia_detalle-> detalle_costo_unitario; 
            $respuesta['PRODUCTOS_TRANSFERIDOS'][$i]['ID'] = $do_transferencia_detalle -> transf_id;
        }


        //NOTAS
        $notas = DB_DataObject::factory('notas');        
        $do_notas = $notas -> getNotas($f_desde,$f_hasta);
        $i=0;
        while($do_notas -> fetch()){
            $i++;
            $respuesta['NOTAS'][$i]['FECHA'] = $do_notas -> nota_fh; 
            $respuesta['NOTAS'][$i]['TIPO'] = $do_notas -> nota_tipo; 
            $respuesta['NOTAS'][$i]['AGENTE'] = $do_notas -> ta_nombre; 
            $respuesta['NOTAS'][$i]['NOMBRE'] = $do_notas -> getAgente(); 
            $respuesta['NOTAS'][$i]['MONTO' ] = $do_notas -> nota_monto; 
            $respuesta['NOTAS'][$i]['OBSERVACION'] = $do_notas -> nota_observacion; 
            $respuesta['NOTAS'][$i]['ID'] = $do_notas -> nota_id; 
        }




         //print_r(($respuesta));exit;
        return $respuesta;
    }

    function getMontoEfectivo($inicio, $final = false)
    {
        if (!$final) {
            $final = date('Y-m-d H:i:s');
        }

        /*
        > suman:
            - cobros clientes

        > restan:
            - Proveedores           
            - Empleados
            - Gastos
        */
        // Primero traigo todos los datos crudos.

        $cobro_cliente = DB_DataObject::factory('cobro_cliente');
        $pagos['Clientes'] = $cobro_cliente->getIngresosCaja($inicio, $final);

        $pago_proveedor = DB_DataObject::factory('pago_proveedor');
        $pagos['Proveedores'] = $pago_proveedor->getIngresosCaja($inicio, $final);

        $pago_cardesc = DB_DataObject::factory('pago_cardesc');
        $pagos['Cardesc'] = $pago_cardesc->getIngresosCaja($inicio, $final);

        $pago_transportista = DB_DataObject::factory('pago_transportista');
        $pagos['Cardesc'] = $pago_transportista->getIngresosCaja($inicio, $final);

        $pago_empleado = DB_DataObject::factory('pago_empleado');
        $pagos['Empleados'] = $pago_empleado->getIngresosCaja($inicio, $final);

        // $gasto = DB_DataObject::factory('gasto');
        // $pagos['Gastos'] = $gasto->getIngresosCaja($inicio, $final);

        $salidas_de_caja = DB_DataObject::factory('view_salidas_de_caja');
        $pagos['Salidas'] = $salidas_de_caja->getSalidasEfectivo($inicio, $final);


        // En Caja
        $pagos['En Caja']['Efectivo']['Entradas'] = 0 + $pagos['Clientes']['Efectivo'];// + $pagos['Cheques']['CambiadosEfectivo'];

        $total = 0 + $this->caja_monto_inicio + $pagos['En Caja']['Efectivo']['Entradas'] - $pagos['Salidas']['Total'];
        // print_r($total);exit;
        return $total;
    }


}
