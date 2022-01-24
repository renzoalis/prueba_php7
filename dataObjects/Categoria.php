<?php
/**
 * Table Definition for categoria
 */
require_once 'DB/DataObject.php';

class DataObjects_Categoria extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'categoria';           // table name
    public $cat_id;                          // int(11)  not_null primary_key auto_increment group_by
    public $cat_nombre;                      // varchar(128)  not_null
    public $cat_baja;                        // tinyint(1)  not_null group_by
    public $cat_tipo_id;                     // int(11)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Categoria',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function getCategorias($id=false) {

        $do_categorias = DB_DataObject::factory('categoria');

        $do_tipo = DB_DataObject::factory('tipo');
        $do_categorias -> joinAdd($do_tipo);

        //$do_categorias -> cat_baja = 0; 
        if($id){
            $do_categorias -> cat_id = $id;
               }

        if($id){
            $do_categorias -> find(true);
        } else {
            $do_categorias -> find();
        }
        return $do_categorias;
    }
    
    function alta_categoria ($objeto) {
        $do_categorias = DB_DataObject::factory('categoria');
        $do_categorias -> cat_nombre = $objeto['input_categoria'];
        $do_categorias -> cat_tipo_id = $objeto['input_tipo'];
        $do_categorias -> cat_baja = 0;
        return $do_categorias -> insert();
    }

    function edit_categoria ($objeto) {
        $do_categorias = DB_DataObject::factory('categoria');
        $do_categorias -> cat_id = $objeto['edit_categoria_id'];
        $do_categorias -> find(true);
        // print_r($do_categorias);exit;

        $do_categorias -> cat_nombre = $objeto['input_categoria_edit'];
        $do_categorias -> cat_tipo_id = $objeto['input_tipo_edit'];
        $do_categorias -> cat_baja = $objeto ['tipoEstado'];
        $respuesta = $do_categorias -> update();

        return $respuesta;

    }

    function prodSinStock() {
        $do_productos = DB_DataObject::factory('producto');
        $do_productos -> prod_cat_id = $this -> cat_id;
        $do_productos -> find();

        $respuesta = 0;

        while($do_productos -> fetch()){
            $respuesta += $do_productos -> getStock();
        }

        return $respuesta;
    }

    function catConStock($tipo_id=false) {

        $do_producto = DB_DataObject::factory('producto');
        $do_ps = DB_DataObject::factory('producto_stock');

        $do_producto -> joinAdd($this);
        $do_ps -> joinAdd($do_producto);

        $do_ps -> selectAdd();
        $do_ps -> selectAdd('DISTINCT cat_nombre, cat_id');
        
        if($tipo_id){
            $do_ps -> whereAdd('ps_cantidad > 0 and cat_tipo_id ='.$tipo_id);
        }else{
            $do_ps -> whereAdd('ps_cantidad > 0');
        }

        $do_ps -> find();
        return($do_ps);
    }




}

