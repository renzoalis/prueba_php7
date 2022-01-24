<?php
/**
 * Table Definition for view_proveedor_con_deuda
 */
require_once 'DB/DataObject.php';

class DataObjects_View_proveedor_con_deuda extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'view_proveedor_con_deuda';    // table name
    public $ccte_id;                         // int(11)  not_null primary_key auto_increment group_by
    public $ccte_proveedor_id;               // int(11)  not_null group_by
    public $ccte_fh;                         // datetime(19)  not_null
    public $ccte_operacion_tipo;             // int(11)  not_null group_by
    public $ccte_operacion_id;               // int(11)  not_null group_by
    public $ccte_importe;                    // float(11)  not_null group_by
    public $ccte_saldo_actual;               // float(11)  not_null group_by
    public $prov_id;                         // int(11)  not_null primary_key auto_increment group_by
    public $prov_nombre;                     // varchar(256)  not_null
    public $prov_email;                      // varchar(256)  
    public $prov_tel1;                       // varchar(128)  
    public $prov_dni;                        // varchar(128)  
    public $prov_observacion;                // blob(65535)  blob
    public $prov_direccion;                  // varchar(256)  
    public $prov_fh_alta;                    // datetime(19)  not_null
    public $prov_baja;                       // tinyint(1)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_View_proveedor_con_deuda',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE



     function getDia($f_desde,$f_hasta){

// DB_DataObject::debugLevel(1);
        $do_proveedores_deuda = DB_DataObject::factory('proveedor_cuenta_corriente');
        $do_proveedores_deuda -> whereAdd('ccte_proveedor_id = '.$this -> ccte_proveedor_id.' AND ccte_fh  between "'.$f_desde.'" and "'.$f_hasta.'" AND ccte_operacion_tipo IN(15,4) ');       
        $do_proveedores_deuda -> find();
        $comprado = 0;
        $pagado = 0;
        while($do_proveedores_deuda -> fetch()){
            if($do_proveedores_deuda -> ccte_operacion_tipo == 15){
                $comprado += $do_proveedores_deuda -> ccte_importe;      // Sumo todo lo comprado
            }elseif($do_proveedores_deuda -> ccte_operacion_tipo == 4){
                $pagado += $do_proveedores_deuda -> ccte_importe;      // Sumo todo lo pagado
            }
        }

        $respuesta['COMPRADO_TOTAL'] = $comprado - $pagado;
        $respuesta['PAGADO_TOTAL'] = $pagado;

        // print_r($do_proveedores_deuda);exit;
        return $respuesta;
    }


    function getUltimo(){

        //ULTIMO FIADO
        $do_proveedores_deuda = DB_DataObject::factory('compra');
        $do_proveedores_deuda -> whereAdd('compra_prov_id = '.$this -> ccte_proveedor_id.' AND compra_monto_total != 0');
        $do_proveedores_deuda -> orderBy('compra_id DESC');
        
        if($do_proveedores_deuda -> find(true)){
            $respuesta['FECHA_COMPRA'] = $do_proveedores_deuda -> compra_fh;
            $respuesta['MONTO_COMPRA'] = $do_proveedores_deuda -> compra_monto_total;
        }else{
            $respuesta['FECHA_COMPRA'] = "";
            $respuesta['MONTO_COMPRA'] = "";
        }

        //ULTIMO COBRO
        $do_proveedores_deuda = DB_DataObject::factory('proveedor_cuenta_corriente');
        $do_proveedores_deuda -> whereAdd('ccte_proveedor_id = '.$this -> ccte_proveedor_id.' AND ccte_operacion_tipo = 4 AND ccte_importe != 0');
        $do_proveedores_deuda -> orderBy('ccte_id DESC');
        if($do_proveedores_deuda -> find(true)){
            $respuesta['FECHA_PAGO'] = $do_proveedores_deuda -> ccte_fh;
            $respuesta['MONTO_PAGO'] = $do_proveedores_deuda -> ccte_importe;
        }else{
            $respuesta['FECHA_PAGO'] = "";
            $respuesta['MONTO_PAGO'] = "";
        }

        return $respuesta;
    }
}
