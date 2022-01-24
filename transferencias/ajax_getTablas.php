<?php

    header('Content-Type: application/json');

    require_once('../config/web.config');
    require_once(CFG_PATH.'/data.config');
    require_once(INC_PATH.'/pear.inc');
    require_once(INC_PATH.'/comun.php');

    $do_transferencias = DB_DataObject::factory('transferencias');
    $transferencias = $do_transferencias -> getTransferencias();
    $transferencias -> find();

    $do_transferenciasEnviadas = DB_DataObject::factory('transferencias');
    $transferenciasEnviadas = $do_transferenciasEnviadas -> getTransferenciasEnviadas();
    $transferenciasEnviadas -> find();

    $do_transferenciasArchivadas = DB_DataObject::factory('transferencias');
    $transferenciasArchivadas = $do_transferenciasArchivadas -> getTransferenciasArchivadas();
    $transferenciasArchivadas -> find();


    require_once('public/tablas.html');
    exit;
?>