<?php
/**
 * Table Definition for log_servicio
 */
require_once 'DB/DataObject.php';

class DataObjects_Log_servicio extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'log_servicio';        // table name
    public $log_id;                          // int(11)  not_null primary_key auto_increment group_by
    public $log_usua_id;                     // int(11)  not_null multiple_key group_by
    public $log_script;                      // int(11)  not_null multiple_key group_by
    public $log_fh;                          // datetime(19)  not_null
    public $log_data_server;                 // varchar(1024)  not_null
    public $log_respuesta;                   // varchar(1024)  not_null

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Log_servicio',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
