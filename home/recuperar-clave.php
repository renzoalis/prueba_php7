<?php
require_once('../config/web.config');

require_once(CFG_PATH.'/data.config');
// PEAR
require_once ('DB.php');
require_once('HTML/QuickForm.php');
require_once 'Mail.php';
//Funciones
require_once(INC_PATH.'/rules.php');

//Creo el form para solicitar el mail
$form = new HTML_QuickForm('SolicitarClave');
$form->addElement('header', null, 'Introduzca su dirección de correo electrónico para obtener una nueva clave.');
$form->addElement('text', 'usua_email', 'Correo electrónico', array('size'=> 30), 'required');
$form->setRequiredNote('* Campos requeridos');
$form->setJsWarnings('Error', 'Por favor corrija estos campos');
$form->addElement('submit', 'enviar', 'Enviar');

//Reglas
$form->applyFilter('usua_email', 'trim');
$form->applyFilter('usua_email', 'strip_tags');
$form->addRule('usua_email', 'Debe ingresar su mail', 'required', null, 'client');
$form->registerRule('checkmail', 'callback', 'checkEmail');
$form->addRule('usua_email', 'El mail es incorrecto', 'checkmail', true);


//Tomo los datos del post
$data = array();
$rc_mensaje = "";
if($form->validate()){
    $data = $form->exportValues();

    //Busco el mail en la base
    $do = DB_DataObject::factory('usuario');
    $do->usua_email = mysql_escape_string($data['usua_email']);
   
    $do->find(true);
    
    if($do->N > 0){
        //Le genero una clave al usuario y la mando por mail
        if(!$do->generarClave($id, $do->usua_email)){
            $tpl->assign('error_msg','Error: No se pudo enviar la nueva clave al usuario.');
        }
        else{
            $rc_mensaje = "Se ha enviado una nueva clave a su dirección de correo electr&oacute;nico.";
        }

        $form->removeElement('enviar');
        $form->freeze();
    }
    else{
        //El mail no esta, mensaje de error
        $rc_mensaje = "El correo electrónico no está en nuestros registros.";
    }
}
//else{
//    $rc_mensaje = "Ocurrio un error al tratar de procesar el formulario, revise los datos ingresados.";
//}


//Llamo a la template
$tpl = new tpl();

// Asigno el form al tpl
$tpl->assign('rc_form', $form->toHtml());

//Mensaje de error o exito
$tpl->assign('rc_mensaje',$rc_mensaje);

//Header
$tpl->assign('webTitulo', WEB_TITULO);
$tpl->assign('secTitulo', WEB_SECCION);


//Template del archivo
$tpl->assign('include_file','recuperar-clave.tpl');

//Mostrar en
$tpl->display('index.tpl');
