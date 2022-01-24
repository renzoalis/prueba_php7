<?php
/**
 * Table Definition for modulo_paginas
 */
require_once 'DB/DataObject.php';

class DataObjects_Modulo_paginas extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'modulo_paginas';      // table name
    public $modpag_id;                       // int(11)  not_null primary_key auto_increment group_by
    public $modpag_mod_id;                   // int(11)  not_null multiple_key group_by
    public $modpag_scriptname;               // varchar(60)  not_null

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Modulo_paginas',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    // Retorna el módulo activo.
    function getModuloActivo(){

        $script_nombre = explode("/", $_SERVER['PHP_SELF']);
        $script_largo = sizeof($script_nombre);

        $script_string = ''.$script_nombre[$script_largo-2].'/'.$script_nombre[$script_largo-1];
        $ses = $_SESSION['usuario'];

        $this -> modpag_scriptname = $script_string;
        $this -> find(true);
       
        return $this -> modpag_mod_id;
    }
}
