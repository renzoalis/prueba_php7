<?php
/**
 * Table Definition for tipo
 */
require_once 'DB/DataObject.php';

class DataObjects_Tipo extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'tipo';                // table name
    public $tipo_id;                         // int(11)  not_null primary_key auto_increment group_by
    public $tipo_nombre;                     // varchar(255)  not_null
    public $tipo_baja;                       // tinyint(1)  not_null group_by
    public $tipo_desc;                       // varchar(255)  

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Tipo',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

   function getTipos($id=false) {
 
        // $do_tipos = DB_DataObject::factory('tipo');
        //$do_tipos -> cat_baja = 0; 
        if($id){
            $this -> tipo_id = $id;
            $this -> find(true);
        }else{
            $this -> find();
        }

        return $this;
    }
    function agregarTipo($objeto) {

        $do_tipo = DB_DataObject::factory('tipo');
        $do_tipo -> tipo_nombre = $objeto['input_tipo'];
        if(!$objeto['input_desc']){
            $do_tipo -> tipo_desc = $objeto['input_tipo'];
        }else {
            $do_tipo -> tipo_desc = $objeto['input_desc'];
        }
        $do_tipo -> tipo_baja = 0;

        $id_insert = $do_tipo -> insert();

        return $id_insert;
    }

    function modificarTipo($objeto) {
        $do_tipo = DB_DataObject::factory('tipo');
        $do_tipo -> tipo_id = $objeto['edit_tipo_id'];
        $do_tipo -> find(true);

        $do_tipo -> tipo_nombre = $objeto['input_tipo_edit'];
        $do_tipo -> tipo_desc = $objeto['input_desc_edit'];
        $do_tipo -> tipo_baja = $objeto['tipoEstado'];

        $id_update = $do_tipo -> update();
        return $id_update;
    }


    function tiposConStock() {

        $do_categoria = DB_DataObject::factory('categoria');
        $do_producto = DB_DataObject::factory('producto');
        $do_ps = DB_DataObject::factory('producto_stock');

        $do_categoria -> joinAdd($this);
        $do_producto -> joinAdd($do_categoria);
        $do_ps -> joinAdd($do_producto);

        $do_ps -> selectAdd();
        $do_ps -> selectAdd('DISTINCT tipo_desc, tipo_id');
        $do_ps -> whereAdd('ps_cantidad > 0');
        $do_ps -> find();
        return($do_ps);
    }
}
