<?php
	function mayorACero($fields) {
		if ($fields['prueba_campo1'] < 0) {
  			return array('prueba_campo1' => 'El valor debe ser mayor a cero');
	    }	    
	    return true;
	}	
	
	function encuentraRol($fields) {
		$do = DB_DataObject::factory('rol');
		$do -> rol_nombre = $fields['rol_nombre'];
		if ($do->find(true)) 
		  {return array('rol_nombre' => 'El nombre ya existe');}
		else 
		  {return true;}		
	}
	
	function encuentraUsuario($fields) {
		$do = DB_DataObject::factory('usuario');
		$do -> usua_nombre = $fields['usua_nombre'];
		$do -> usua_email = $fields['usua_email'];
		if ($do->find(true)) 
		  {return array('usua_nombre' => 'El nombre ya existe' , 'usua_email' => 'El mail ya existe');}
		else 
		  {return true;}		
	}
	
	function buscoCliente($fields) {
		exit;
		$do = DB_DataObject::factory('clientes');
		$do -> cli_cod_suministro = $fields['codigo_suministro'];
		if ($do->find(true)) 
		  {return array('danioart_cod_sum' => 'No existe cliente');}
		else 
		  {return true;}		
	}
	
	function encuentraAplicacion($fields) {
		$do = DB_DataObject::factory('aplicacion');
		$do -> app_nombre = $fields['app_nombre'];
		if ($do->find(true)) 
		  {return array('app_nombre' => 'El nombre ya existe');}
		else 
		  {return true;}		
	}
	
	function encuentraTipoAcceso($fields) {
		$do = DB_DataObject::factory('tipo_acceso');
		$do -> tipoacc_nombre = $fields['tipoacc_nombre'];
		if ($do->find(true)) 
		  {return array('tipoacc_nombre' => 'El nombre ya existe');}
		else 
		  {return true;}		
	}
	
	function encuentraModulo($fields) {
		$do = DB_DataObject::factory('modulo');
		$do -> mod_nombre = $fields['mod_nombre'];
		if ($do->find(true)) 
		  {return array('mod_nombre' => 'El nombre ya existe');}
		else 
		  {return true;}		
	}
	
	function buscaUsuario($fields) {
		$do_aux = DB_DataObject::factory('usuario');
		$do_aux->whereAdd('usua_id <>'.$fields['usua_id']);
		$do_aux->usua_usrid = $fields['usua_usrid'];
		if($do_aux->find(true))
			{return array('usua_nombre' => 'El nombre ya existe');}
		else 
			 {return true;}		
		
	}
	
	function buscaEmailUsuario($fields) {
		$do_aux = DB_DataObject::factory('usuario');
		$do_aux->whereAdd('usua_id <>'.$fields['usua_id']);
		$do_aux->usua_email = $fields['usua_email'];
		if($do_aux->find(true))
			{return array('usua_email' => 'El mail ya existe en la base para otro usuario');}
		else 
			 {return true;}		
		
	}
	
	//busca si un determinado usuario ya tiene ese rol.
	function encuentraRolUsuario($fields) {
		$do = DB_DataObject::factory('usuario_rol');
		$do -> usrrol_usua_id = $fields['usrrol_usua_id'];
		$do -> usrrol_rol_id = $fields['usrrol_rol_id'];
		if ($do->find(true)) 
		  {return array('usrrol_rol_id' => 'El usuario ya tiene ese rol');}
		else 
		  {return true;}		
	}	
	
 /**
 * Validate an email address
 *
 * @param string    $email         Email address to validate
 * @param boolean   $domainCheck   Check if the domain exists
 */
    function checkEmail($email, $domainCheck = false)
    {
        if (preg_match('/^[a-zA-Z0-9\._-]+\@(\[?)[a-zA-Z0-9\-\.]+'.
                   '\.([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/', $email)) {
            //        if ($domainCheck && function_exists('checkdnsrr')) {
            //            list (, $domain)  = explode('@', $email);
            //            if (checkdnsrr($domain, 'MX') || checkdnsrr($domain, 'A')) {
            //                return true;
            //            }
            //            return false;
            //        }
            return true;
        }
        return false;
    }

    /**
     * Valida que sea introduzca un valor entre 0 y 100
     * @param <number> $fields
     * @return <boolean>
     */
    function ValidaPorcentaje($fields = 0) {
        
//        if($tipo == 'porc'){
            if($fields > 100 || $fields < 0){
                return false;
            }
            else{
                return true;
            }
//        }
//        else{
//            return true;
//        }
    }


    /**
     * Valida que sea un numero entero
     * @param <number> $fields
     * @return <boolean>
     */
    function ValidaNumero($fields = 0) {

        if(stripos($fields,'.')===false){
            return true;
        }
        else{
            return false;
        }
	}


    /**
     * Verifica que no este cargando una cantidad de semenstres mayor a la que existen en la base.
     * @param <int> $fields
     * @return <boolean> 
     */

	function ValidaCantSemestres($fields = 0) {
		$do = DB_DataObject::factory('semestres');
        $cantidad = $do->find(true);

		if ($cantidad < $fields){
            return false;
        }
        else{
            return true;
         }
	}

    /**
     * Verifica que el select no este vacio
     * @param <int> $fields
     * @return <boolean>
     */
	function validaSemestres($fields = 0) {
    
		if ($fields == 0){
            return false;
        }
        else{
            return true;

         }
	}

