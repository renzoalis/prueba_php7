<?php
/**
 * Table Definition for perdida_mercaderia
 */
require_once 'DB/DataObject.php';

class DataObjects_Perdida_mercaderia extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'perdida_mercaderia';    // table name
    public $perdida_id;                      // int(11)  not_null primary_key auto_increment group_by
    public $perdida_ps_id;                   // int(11)  not_null group_by
    public $perdida_desc;                    // varchar(512)  
    public $perdida_cantidad;                // int(11)  group_by
    public $perdida_fh;                      // datetime(19)  not_null
    public $perdida_usua_id;                 // int(11)  not_null group_by
    public $perdida_tipo_op;                 // int(11)  not_null group_by
    public $perdida_op_id;                   // int(11)  not_null group_by
    public $perdida_prod_nombre;             // blob(65535)  blob

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Perdida_mercaderia',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    // Tipos:
    //
    // 1: venta
    // 2: transferencia
    // 3: conciliacion

    function getListado($desde = false,$hasta = false){
        $do_usuario = DB_DataObject::factory('usuario');
        $do_ps = DB_DataObject::factory('producto_stock');

        $this -> joinAdd($do_usuario);
        $this -> joinAdd($do_ps);

        if($desde && $hasta){
            $this -> whereAdd('perdida_fh BETWEEN "'.$desde.'" AND "'.$hasta.'"');
        }

        $this -> orderBy('perdida_id DESC');
        $this -> find();

        return $this;
    }
}
