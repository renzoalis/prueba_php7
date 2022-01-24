<?php
/**
 * Table Definition for usuario
 */
require_once 'DB/DataObject.php';

class DataObjects_Usuario extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'usuario';             // table name
    public $usua_id;                         // int(11)  not_null primary_key auto_increment group_by
    public $usua_usrid;                      // varchar(50)  not_null
    public $usua_nombre;                     // varchar(100)  
    public $usua_pwd;                        // varchar(32)  not_null
    public $usua_email;                      // varchar(100)  not_null
    public $usua_tel1;                       // varchar(45)  
    public $usua_tel2;                       // varchar(45)  
    public $usua_baja;                       // tinyint(1)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Usuario',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function getUsuarios($mismaArea){
    
        if ($mismaArea){
            //Encuentro el area del propietario
            $do_usuario_area_propietario = DB_DataObject::factory('usuario_area');
            $do_usuario_area_propietario -> usarea_usua_id = $_SESSION['usuario']['id'];
            $do_usuario_area_propietario -> find(true);
            //Encuentro todos los usuarios de esa area
            $do_usuario_area_users = DB_DataObject::factory('usuario_area');
            $do_usuario_area_users -> usarea_area_id = $do_usuario_area_propietario -> usarea_area_id;
            $do_usuario_area_users -> find();
            //Hago un join con usuarios
            $do_usuario = DB_DataObject::factory('usuario');
            $do_usuario_area_users -> joinAdd($do_usuario);
            $do_usuario_area_users -> find();
            return $do_usuario_area_users;  
        } else {
            $do_usuario = DB_DataObject::factory('usuario');
            $do_usuario -> find();
            return $do_usuario;
        }
    }

    function esAdmin(){

        $respuesta = false;

        $do_usuario_rol = DB_DataObject::factory('usuario_rol');
        $do_usuario_rol -> usrrol_app_id = APP_ID;
        $do_usuario_rol -> usrrol_usua_id = $_SESSION['usuario']['id'];
        $do_usuario_rol -> find(true);

        if($do_usuario_rol -> usrrol_rol_id == 1) {
            $respuesta = true;
        }
        return $respuesta;
    }

    function esPremium(){

        $respuesta = 0;

        $do_usuario_rol = DB_DataObject::factory('usuario_rol');
        $do_usuario_rol -> usrrol_app_id = APP_ID;
        $do_usuario_rol -> usrrol_usua_id = $_SESSION['usuario']['id'];
        $do_usuario_rol -> find(true);

        if($do_usuario_rol -> usrrol_rol_id == 4) {
            $respuesta = 1;
        }

        return $respuesta;
    }
    function esUsuarioBasic(){

        $respuesta = 0;

        $do_usuario_rol = DB_DataObject::factory('usuario_rol');
        $do_usuario_rol -> usrrol_app_id = APP_ID;
        $do_usuario_rol -> usrrol_usua_id = $_SESSION['usuario']['id'];
        $do_usuario_rol -> find(true);

        if($do_usuario_rol -> usrrol_rol_id == 2) {
            $respuesta = 1;
        }

        return $respuesta;
    }

}
