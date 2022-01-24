<?php
require_once('../config/web.config');

require_once(CFG_PATH.'/data.config');
// PEAR
require_once ('DB.php');
require_once('HTML/QuickForm.php');
require_once 'Mail.php';
//Funciones
require_once(INC_PATH.'/rules.php');

$reset_mensaje = "";
//Si es usuarios y tiene identificacion
if($_GET['id'] > 0){
    $usua_id = (int) $_GET['id'];

    $do = DB_DataObject::factory('usuario');
    $do->usua_id = $usua_id;
    
    if($do->find(true)){
        $do->generarClave($usua_id,$do->usua_email);
        $reset_mensaje = "Se gener&oacute; una nueva clave para el usuario y se la envi&oacute; a la direcci&oacute;n de correo electr&oacute;nico del usuario.";
    }
}
else{
    $reset_mensaje = "No se realizaron cambios en la base de datos.";
}

//Llamo a la template
$tpl = new tpl();
//Mensaje de error o exito
$tpl->assign('reset_mensaje',$reset_mensaje);

//Header
$tpl->assign('webTitulo', WEB_TITULO);
$tpl->assign('secTitulo', WEB_SECCION);


//Template del archivo
$tpl->assign('menu','menu_oceba.tpl');
$tpl->assign('include_file','resetear-clave.tpl');

//Mostrar en
$tpl->display('index.tpl');
