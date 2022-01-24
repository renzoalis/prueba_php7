<?php
/**
 * Table Definition for view_pagos_otros
 */
require_once 'DB/DataObject.php';

class DataObjects_View_pagos_otros extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'view_pagos_otros';    // table name
    public $ID;                              // int(11)  not_null group_by
    public $FECHA;                           // datetime(19)  not_null
    public $ENTIDAD;                         // varchar(14)  not_null
    public $ENTIDAD_ID;                      // int(11)  not_null group_by
    public $MONTO;                           // float(11)  not_null group_by
    public $USUARIO;                         // int(11)  not_null group_by
    public $USUARIO_NOMBRE;                  // varchar(100)  
    public $FORMA_PAGO;                      // varchar(45)  not_null
    public $FORMA_PAGO_ID;                   // int(11)  not_null group_by
    public $OBS;                             // varchar(256)  

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_View_pagos_otros',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    function getIngresoOtros($fi,$ff=false) {

        if(!$ff){
            $ff = date('Y-m-d H:i:s');
        }
        $do_pagos_otros = DB_DataObject::factory('view_pagos_otros');

        if($fi){
            $do_pagos_otros -> whereAdd('FECHA between "'.$fi.'" and "'.$ff.'"');
        }
        $do_pagos_otros -> find();
        
        $respuesta['Total'] = 0;
        while($do_pagos_otros -> fetch()){
            $respuesta['Total'] += $do_pagos_otros -> MONTO;
        }

        return $respuesta['Total'];

    }


    function getIngresosCaja($fhInicio,$fhCierre=false) {
        if(!$fhCierre){
            $fhCierre = date('Y-m-d H:i:s');
        }
        $this -> whereAdd('FECHA BETWEEN "'.$fhInicio.'" AND  "'.$fhCierre.'"');

        $this -> find();
        $suma = 0;

        while($this -> fetch()){
            $total[$this -> FORMA_PAGO] += 0;
            $total[$this -> FORMA_PAGO] += $this -> MONTO;
            $suma += $this ->  MONTO;
        }
        $total['Total'] = $suma;

        return $total;
    }
}
