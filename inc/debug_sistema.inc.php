<?php
	function debug_sistema($string,$nivel = 1) {
		if(ECHO_DEBUG >= $nivel) {
				list($sec,$usec) = explode(' ',microtime());
				$retorno = date("Y-m-d H:i:s").".$usec ".$string;
				$retorno .= "<br/>";
			}
			echo $retorno;
	}
?>