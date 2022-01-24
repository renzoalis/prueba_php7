<?php
/**
 * Table Definition for cardesc
 */
require_once 'DB/DataObject.php';

class DataObjects_Cardesc extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'cardesc';             // table name
    public $cardesc_id;                      // int(11)  not_null primary_key group_by
    public $cardesc_nombre;                  // varchar(256)  not_null
    public $cardesc_baja;                    // int(1)  not_null group_by

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Cardesc',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
