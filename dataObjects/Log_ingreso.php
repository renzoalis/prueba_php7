<?php
/**
 * Table Definition for log_ingreso
 */
require_once 'DB/DataObject.php';

class DataObjects_Log_ingreso extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'log_ingreso';         // table name
    public $loging_id;                       // int(11)  not_null primary_key auto_increment group_by
    public $loging_usua_id;                  // int(11)  not_null multiple_key group_by
    public $loging_app_id;                   // int(11)  not_null multiple_key group_by
    public $loging_fecha;                    // datetime(19)  not_null

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Log_ingreso',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
