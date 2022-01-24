<?php
	
	/*require_once('config/web.config');
	
	session_name(WWW_DIR.PUESTO_ID);
session_start();
	session_destroy();
	header('Location:'.PGN_LOGIN);
	exit;*/

       require_once('config/web.config');

        session_name(WWW_DIR.PUESTO_ID);
session_start();
        $acceso_general = 0;
        if(isset($_SESSION['acceso'])) $acceso_general = 1;
        session_destroy();
        if($_SESSION['distribuidora'] == 1) {
            if ($_SESSION['sitio'] == 'prod')
                header('Location:http://distribuidora.frlp.utn.edu.ar/home/logout.php');
            else
            header('Location:'.PGN_LOGOUT_DIST);
        } else {
                if($acceso_general)
                        header('Location:http://expedientes.frlp.utn.edu.ar/acceso/login.php');
                else
                        header('Location:'.PGN_LOGIN);
        }
        exit;


?>
