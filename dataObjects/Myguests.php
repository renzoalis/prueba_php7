<?php
/**
 * Table Definition for myguests
 */
require_once 'DB/DataObject.php';

class DataObjects_Myguests extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'myguests';            // table name
    public $id;                              // int(6)  not_null primary_key unsigned auto_increment group_by
    public $firstname;                       // varchar(30)  not_null
    public $lastname;                        // varchar(30)  not_null
    public $email;                           // varchar(50)  
    public $reg_date;                        // timestamp(19)  not_null timestamp

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Myguests',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
