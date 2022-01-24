<?php
/**
 * Table Definition for view_usuario_login
 */
require_once 'DB/DataObject.php';

class DataObjects_View_usuario_login extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'view_usuario_login';    // table name
    public $usrrol_id;                       // int(11)  not_null primary_key auto_increment group_by
    public $usua_id;                         // int(11)  not_null primary_key auto_increment group_by
    public $usua_usrid;                      // varchar(50)  not_null
    public $usua_nombre;                     // varchar(100)  
    public $usua_pwd;                        // varchar(32)  not_null
    public $usua_email;                      // varchar(100)  not_null
    public $rol_nombre;                      // varchar(45)  not_null
    public $app_id;                          // int(11)  not_null primary_key auto_increment group_by
    public $app_nombre;                      // varchar(45)  not_null
    public $permiso_id;                      // int(11)  not_null primary_key auto_increment group_by
    public $tipoacc_id;                      // int(11)  not_null primary_key auto_increment group_by
    public $tipoacc_nombre;                  // varchar(45)  unique_key
    public $mod_id;                          // int(11)  not_null primary_key auto_increment group_by
    public $mod_nombre;                      // varchar(45)  not_null
    public $mod_baja;                        // tinyint(4)  not_null group_by
    public $modpag_id;                       // int(11)  not_null primary_key auto_increment group_by
    public $modpag_scriptname;               // varchar(60)  not_null

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_View_usuario_login',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function getModulos(){
        $modulos_menu = DB_DataObject::factory('view_usuario_login');
        $modulos_menu -> app_id = APP_ID;
        $modulos_menu -> whereAdd('mod_baja != 1');
        $modulos_menu -> usua_id = $_SESSION['usuario']['id'];
        $modulos_menu -> find();
        // echo "HOLA";exit;
        $tarjetas_menu = array();
        while ($modulos_menu -> fetch()){
            $do_modulos = DB_DataObject::factory('modulo');
            $do_modulos -> mod_id = $modulos_menu -> mod_id;
            $do_modulos -> find(true);

            $do_modulos_paginas = DB_DataObject::factory('modulo_paginas'); //TRAIGO EL INDEX
            $do_modulos_paginas -> modpag_id = $do_modulos -> mod_index_modpag_id;
            $do_modulos_paginas -> find(true);

            $tarjetas_menu[$do_modulos -> mod_id]['modulo']['mod_icono'] = $do_modulos -> mod_icono;
            $tarjetas_menu[$do_modulos -> mod_id]['modulo']['mod_color'] = $do_modulos -> mod_color;
            $tarjetas_menu[$do_modulos -> mod_id]['modulo']['mod_nombre'] = $do_modulos -> mod_nombre;
            $tarjetas_menu[$do_modulos -> mod_id]['modulo']['mod_intro'] = $do_modulos -> mod_intro;
            $tarjetas_menu[$do_modulos -> mod_id]['index'] = $do_modulos_paginas -> modpag_scriptname;

            $mp = DB_DataObject::factory('modulo_paginas');  //TRAIGO EL ACTIVO

            if($mp -> getModuloActivo() == $do_modulos -> mod_id){
                $tarjetas_menu[$do_modulos -> mod_id]['modulo']['mod_clase'] = 'active';
            } else {
                $tarjetas_menu[$do_modulos -> mod_id]['modulo']['mod_clase'] = '';
            }
        }

        return $tarjetas_menu;
    }
}
