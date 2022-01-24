<?php
/**
 * Table Definition for calibre
 */
require_once 'DB/DataObject.php';

class DataObjects_Calibre extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'calibre';             // table name
    public $calibre_id;                      // int(11)  not_null primary_key group_by
    public $calibre_descripcion;             // char(255)  not_null
    public $calibre_baja;                    // tinyint(1)  not_null group_by
    public $asd;                             // varchar(15)  not_null

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Calibre',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
