<?php
/**
 * Table Definition for _actualizacion
 */
require_once 'DB/DataObject.php';

class DataObjects__actualizacion extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = '_actualizacion';      // table name
    public $act_id;                          // int(11)  not_null primary_key auto_increment group_by
    public $act_version;                     // varchar(256)  not_null
    public $act_fh;                          // datetime(19)  not_null
    public $act_script;                      // varchar(256)  not_null

    /* Static get */
    //function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects__actualizacion',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    /* COMO FUNCIONA? 
    ** 
    ** Este script sirve para actualizar la estructura y datos de la BD en local, y así tener todos la misma base.
    ** Usos comunes: nuevo modulo_pagina, nuevas clases, datos fijos en tablas (por ej tablas *_tipo), etc.
    ** 
    ** Se llama a la funcion _actualizacion -> getUltimoUpdate(), que devuelve la fecha de la útlima versión de su BD. 
    ** Si la fecha es menor a la de ESTE script, se corre la funcion _actualizacion -> nuevoUpdate($o), que agrega la 
    ** última actualización a esta tabla y corre el script correspondiente. 
    */


    function getUltimoUpdate(){
        /* DATOS A MODIFICAR CON CADA UPDATE */
        $o['act_version'] = '5.0.1'; 
        $o['act_fh'] = '2019-12-09 18:30:01'; // Formato: 2019-10-08 18:30:00
        $o['act_script'] = '../scripts/_actualizacion.sql'; // Arhcivo SQL

        /* DATOS FIJOS DE LA BASE EN LOCAL */
        $o['host'] = "localhost";
        $o['database'] = "mercadocentral";
        $o['uname'] = "root";
        $o['pass'] = "";

        /* LLAMAMOS AL SCRIPT QUE PREGUNTA */
        $update_check = DB_DataObject::factory('_actualizacion');
        $update_check -> orderBy('act_id DESC');
        $update_check -> find(true);

        if($update_check -> act_fh < $o['act_fh']) { // Hay actualizacion!
            $update_nuevo = DB_DataObject::factory('_actualizacion');
            $id = $update_nuevo -> nuevoUpdate($o);
            if($id) {
                $respuesta['version'] = $o['act_version'];
                $respuesta['update'] = true;
            } else {
                $respuesta['texto'] = 'Error en el Script .sql!';
                $respuesta['error'] = '100';
            }
        } else {
            $respuesta['version'] = $o['act_version'];
            $respuesta['fecha'] = $o['act_fh'] = date('d/m/Y',strtotime($update_check -> act_fh)); 
        }

        return $respuesta;
    }

    function nuevoUpdate($o){
        /* DATOS FIJOS DE LA BASE EN LOCAL */
        $host =  $o['host'];
        $uname = $o['uname'];
        $pass = $o['pass'];
        $database = $o['database'];
         
        /* SCRIPT SQL */
        $filename = $o['act_script']; 

        /* CONEXION Y QUERYS */
        $conn = new mysqli($host, $uname, $pass, $database);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $cant = 0;
        $op_data = '';
        $lines = file($filename);

        foreach ($lines as $line)
        {
            if (substr($line, 0, 2) == '--' || $line == '')//This IF Remove Comment Inside SQL FILE
            {
                continue;
            }
            $op_data .= $line;
            if (substr(trim($line), -1, 1) == ';')//Break Line Upto ';' NEW QUERY
            {
                if ($conn->query($op_data) === TRUE) {
                    //echo "Operacion exitosa <br>";
                } else {
                    echo "Error con la operacion: " . $conn->error;
                }
                $op_data = '';
                $cant ++;
            }
        }

        $conn -> close();

        /* INSERTO LA ACTUALIZACION */
        if($cant){
            // DB_DataObject::debugLevel(1);
            $inserto = DB_DataObject::factory('_actualizacion');
            $inserto -> act_version = $o['act_version'];
            $inserto -> act_fh = $o['act_fh'];
            $inserto -> act_script = $o['act_script'];

            $id = $inserto -> insert();
            // print_r($id);
        }
        
        if($id){
            return $id;
        } else {
            return false;
        }
    }



}
