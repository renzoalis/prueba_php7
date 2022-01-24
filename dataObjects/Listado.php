<?php
/**
 * Table Definition for listado
 */
require_once 'DB/DataObject.php';

class DataObjects_Listado extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'listado';             // table name
    public $cat;                             // varchar(255)  
    public $prod;                            // varchar(255)  
    public $variedad;                        // varchar(255)  
    public $calibre;                         // varchar(255)  
    public $pres;                            // varchar(255)  
    public $detalle;                         // varchar(255)  

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Listado',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
