<?php
/**
 * Table Definition for view_cliente_con_deuda
 */
require_once 'DB/DataObject.php';

class DataObjects_View_cliente_con_deuda extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'view_cliente_con_deuda';    // table name
    public $ccte_id;                         // int(11)  not_null primary_key auto_increment group_by
    public $ccte_cliente_id;                 // int(11)  not_null group_by
    public $ccte_fh;                         // datetime(19)  not_null
    public $ccte_operacion_tipo;             // int(11)  not_null group_by
    public $ccte_operacion_id;               // int(11)  not_null group_by
    public $ccte_importe;                    // float(11)  not_null group_by
    public $ccte_saldo_actual;               // float(11)  not_null group_by
    public $cliente_id;                      // int(11)  not_null primary_key auto_increment group_by
    public $cliente_nombre;                  // varchar(256)  not_null
    public $cliente_email;                   // varchar(128)  
    public $cliente_tel1;                    // varchar(64)  
    public $cliente_trabajo;                 // varchar(64)  
    public $cliente_dni;                     // varchar(64)  
    public $cliente_observacion;             // blob(65535)  blob
    public $cliente_direccion;               // varchar(256)  
    public $cliente_fh_alta;                 // datetime(19)  not_null
    public $cliente_baja;                    // tinyint(1)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_View_cliente_con_deuda',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE


    function getDia($f_desde,$f_hasta){


        $do_clientes_deuda = DB_DataObject::factory('cliente_cuenta_corriente');
        $do_clientes_deuda -> whereAdd('ccte_cliente_id = '.$this -> ccte_cliente_id.' AND ccte_fh  between "'.$f_desde.'" and "'.$f_hasta.'" AND ccte_operacion_tipo IN(1,3) ');       
        $do_clientes_deuda -> find();
        $vendido = 0;
        $pagado = 0;
        while($do_clientes_deuda -> fetch()){
            if($do_clientes_deuda -> ccte_operacion_tipo == 1){
                $vendido += $do_clientes_deuda -> ccte_importe;      // Sumo todo lo vendido
            }elseif($do_clientes_deuda -> ccte_operacion_tipo == 3){
                $pagado += $do_clientes_deuda -> ccte_importe;      // Sumo todo lo pagado
            }
        }

        $respuesta['FIADO_TOTAL'] = $vendido - $pagado;
        $respuesta['COBRADO_TOTAL'] = $pagado;

        return $respuesta;
    }


    function getUltimo(){

        //ULTIMO FIADO
        $do_clientes_deuda = DB_DataObject::factory('venta');
        $do_clientes_deuda -> whereAdd('venta_cliente_id = '.$this -> ccte_cliente_id.' AND venta_forma_pago_id = 10');
        $do_clientes_deuda -> orderBy('venta_id DESC');
        $do_clientes_deuda -> find(true);


        $respuesta['FECHA_FIADO'] = $do_clientes_deuda -> venta_fh;
        $respuesta['MONTO_FIADO'] = $do_clientes_deuda -> venta_monto_total;

        //ULTIMO COBRO
        $do_clientes_deuda = DB_DataObject::factory('cliente_cuenta_corriente');
        $do_clientes_deuda -> whereAdd('ccte_cliente_id = '.$this -> ccte_cliente_id.' AND ccte_operacion_tipo = 3 AND ccte_importe != 0');
        $do_clientes_deuda -> orderBy('ccte_id DESC');
        $do_clientes_deuda -> find(true);
        
        $respuesta['FECHA_COBRO'] = $do_clientes_deuda -> ccte_fh;
        $respuesta['MONTO_COBRO'] = $do_clientes_deuda -> ccte_importe;
        // print_r($respuesta);exit;

        return $respuesta;
    }
}
