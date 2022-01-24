<?php
Class Encript {
	const patron_busqueda = "qpwoeirutyQPWOEIRUTYañsld1234567890/_-kfjghAÑSLDKFJGHzmxncbvZMXNCBV.";
	const Patron_encripta = "zmxncbvZMXNCBVañsldkfjghAÑ./_-SLDKFJGHqpwoeirutyQPWOEIRUTY0987654321";

	private static function InStr($haystack,$caracter= null) {
		$pos = strpos($haystack,$caracter);	
		if ($pos !== false) {
			return $pos+1;
		}
		else
			return false;
	}
	
	private static function Mid($str,$start,$lenght) {
		if ($start < 1)
			return "";
		else
			return substr($str, $start-1, $lenght);
	}
	
	public static function EncriptarCadena($cadena) {
		$idx = 1;
		$result = '';	 
		for ($idx = 1;$idx < strlen($cadena)+1;$idx++) {
			$result = $result . Encript::EncriptarCaracter(Encript::Mid($cadena, $idx, 1), strlen($cadena), $idx);
		}		
		return $result;
    }
	
	private static function EncriptarCaracter($caracter, $variable,$a_indice) {
		$caracterEncriptado = '';
		$indice = 0;
		if (Encript::InStr(Encript::patron_busqueda,$caracter) !== false) {
			
			$a = Encript::InStr(Encript::patron_busqueda,$caracter) + $variable + $a_indice;
			$b = strlen(Encript::patron_busqueda);		
			$indice = $a%$b;
			if ($indice % strlen(Encript::Patron_encripta) == 0) {
				$indice = strlen(Encript::Patron_encripta);	
				$result = Encript::Mid(Encript::Patron_encripta, $indice, 1);				
			}
			else {				
				$indice = $indice%strlen(Encript::Patron_encripta);
				$result = Encript::Mid(Encript::Patron_encripta, $indice, 1);
			}		
			
		}
		else {
			$result = $caracter;
		}
		return $result;
		//EncriptarCaracter = caracter
	}
	
	public static function DesEncriptarCadena($cadena) {
		$idx = 1;
		$result = '';
		for ($idx = 1;$idx < strlen($cadena)+1;$idx++) {
			$result = $result.Encript::DesEncriptarCaracter(Encript::Mid($cadena, $idx, 1), strlen($cadena), $idx);
		}
		//DesEncriptarCadena = result
		return $result;
	 }

	private static function DesEncriptarCaracter($caracter, $variable, $a_indice) {
		$indice = 0;
		$result = '';
		$pos = Encript::InStr(Encript::Patron_encripta, $caracter);
		if ($pos !== false) {
			$a = $pos - $variable - $a_indice;
			if ($a > 0) {				
				$b = strlen(Encript::Patron_encripta);
				$indice = $a%$b;
			}
			else {
				 //La línea está cortada por falta de espacio        
				$indice = strlen(Encript::patron_busqueda) + ($a)%strlen(Encript::Patron_encripta);
			}
			
			If ($indice%strlen(Encript::Patron_encripta) == 0) {
				$indice = strlen(Encript::Patron_encripta);
			}
			else {
				$indice = $indice%strlen(Encript::Patron_encripta);
			}
			
			$result = Encript::Mid(Encript::patron_busqueda, $indice, 1);
		}
		else {
			$result = $caracter;
		}
		return $result;
	}	
	
	public static function testEncriptarCaracter() {
		return Encript::EncriptarCaracter('a',1,1);
	}
	public static function testDesEncriptarCaracter() {
		return Encript::DesEncriptarCaracter('h',1,1);
	}
	
	public static function testEncriptarCadena() {		
		require_once('rand_word.class.php');
		srand(microtime());	
		echo '
		<table
			<tr>
				<td>Valor</td><td>Encriptado</td><td>Desencriptado</td>
		';
		
		for ($i = 0;$i < 21;$i++) {		
			$word = new rand_word(rand(6,12));	
			$val = $word->word;
				
			$var = Encript::EncriptarCadena($val);
			$res = Encript::DesEncriptarCadena($var);
			echo '<tr><td>'.$val.'</td><td>'.$var.'</td><td>'.$res.'</td></tr>';		
		}
		echo '</table>';
	}	
}
?>