<?php
/**
 * Table Definition for view_salidas_de_caja
 */
require_once 'DB/DataObject.php';

class DataObjects_View_salidas_de_caja extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'view_salidas_de_caja';    // table name
    public $categoria;                       // varchar(255)  not_null
    public $concepto;                        // varchar(352)  
    public $fecha;                           // datetime(19)  
    public $nombre;                          // varchar(256)  
    public $monto;                           // float(12)  not_null group_by
    public $observacion;                     // varchar(256)  
    public $id;                              // int(11)  not_null group_by
    public $forma_pago;                      // varchar(45)  not_null
    public $op_id;                           // varchar(14)  not_null
    public $fp_id;                           // varchar(11)  not_null

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_View_salidas_de_caja',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function getMontoSalidasDeCaja($fi,$ff=false) {

        if(!$ff){
            $ff = date('Y-m-d H:i:s');
        }
        $do_salidas_de_caja = DB_DataObject::factory('view_salidas_de_caja');

        if($fi){
            $do_salidas_de_caja -> whereAdd('fecha between "'.$fi.'" and "'.$ff.'"');
        }
        $do_salidas_de_caja -> find();
        // print_r($do_salidas_de_caja);exit;
        $respuesta['Total'] = 0;
        while($do_salidas_de_caja -> fetch()){
            $respuesta['Total'] += $do_salidas_de_caja -> monto;
        }

        return $respuesta['Total'];

    }


    function getSalidasEfectivo($fhInicio,$fhCierre=false) {
        if(!$fhCierre){
            $fhCierre = date('Y-m-d H:i:s');
        }

        $this -> whereAdd('fecha BETWEEN "'.$fhInicio.'" AND  "'.$fhCierre.'" AND fp_id = 1');
        $this -> find();
        $suma = 0;

        while($this -> fetch()){
            $total['Efectivo'] += $this -> monto;
            $suma += $this ->  monto;
        }
        $total['Total'] = $suma;

        return $total;
    } 
}
