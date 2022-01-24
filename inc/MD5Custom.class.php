<?php 
	//include_once 'Md5Custom2.php';

	define('OFFSET_4',4294967296);
	define('MAXINT_4',2147483647);
	define('S11',70); //7
	define('S12',12);
	define('S13',17);
	define('S14',22);
	define('S21',5);
	define('S22',9);
	define('S23',14);
	define('S24',20);
	define('S31',4);
	define('S32',11);
	define('S33',16);
	define('S34',23);
	define('S41',60); //6
	define('S42',10);
	define('S43',15);
	define('S44',21);
	
	class md5Custom {	
		var $state;
		var $byteCounter;
		var $byteBuffer;				
				
		private function _llof($fp) {
            $p_current=ftell($fp);
            $rtn=fgets($fp)?false:true;
            fseek($fp, $p_current);
            return $rtn;
	    } 
		
		Public Function DigestFileToHexStr($fileName) {   
	    	
		   $fp = fopen($fileName);
		   $this->_MD5Init();			
		    While (!feof($fp)) {
		        $this->byteBuffer = fgets($fp);
		        If ($this->_llof($fp)) {
		            $this->byteCounter = $this->byteCounter + 64;
		             $this->_MD5Transform($this->byteBuffer);
		        }
		    }
		    
			$this->byteCounter = $this->byteCounter + (filesize($fp)%64);		   
		    fclose($fp);			
		    return $this->_GetValues();
		}		
		
		Function DigestStrToHexStr($SourceString) {			
			$string = $SourceString;
			$len = strlen($string) * 8;
	    		
			$this->_MD5Init();
			
			$input = $this->_StringToArray($SourceString);
			
			$wLen = (((count($input) + 8) >> 6) + 1) << 4;
							
			$X = array_fill(0, $wLen, null);
			
			$X[0] = 0x80;
						
			if (($SourceString) or $SourceString === 0) {				
				/* Convert string to array of words */
				$j = 4;
				for ($i = 0; ($i * 4) < count($input); $i++) {
					$X[$i] = 0;
					for ($j = 0; ($j < 4) && (($j + ($i * 4)) < count($input)); $j++) {
						$ascii = ord($input[(($i * 4) + $j)]);					
						$X[$i] += ($ascii << ($j * 8));					
					}
				}			
				
				/* Append padding bits and length */
				if ($j == 4)	{
					$X[$i++] = 0x80;
					
				}
				else {
					$X[$i - 1] += 0x80 << ($j * 8);			
				}
				
				for(; $i < $wLen; $i++) { 
					$X[$i] = 0x00000000;
				}
				$X[$wLen - 2] = count($input) * 8;
			}			
			
			/* Calculate length in machine words, including padding */				
			
						
			for ($i = 0; $i < $wLen; $i += 16) {
				for ($j = 0; $j < 16;$j++) {
					$A[$j] = $X[$i+$j];
				}			
				$this->_MD5Transform($A);
			}			
					
			return $this->_GetValues();
		}
		
		/**
		* A utility function which converts a string into an array of
		* bytes.
		*/
		private function _StringToArray($InString) {
		    $input = str_split($InString);
		    return $input;			
		}
		
		/**
		* Concatenate the four state vaules into one string
		**/
		private function _GetValues() {
			$s1 = $this->_LongToString($this->state[1]);
			$s2 = $this->_LongToString($this->state[2]);
			$s3 = $this->_LongToString($this->state[3]);
			$s4 = $this->_LongToString($this->state[4]);			
			return $s1.$s2.$s3.$s4;	
		}
	
		private function _LongToString($Num) {
			$a = $Num & 0xFF;
			
			If ($a < 16)
				$LongToString = "0" . dechex($a);
			Else
				$LongToString = dechex($a);
			
				   
			$b = (int) ($Num & 0xFF00) / 256;	
			
			If ($b < 16)
				$LongToString = $LongToString."0".dechex($b);
			Else
				$LongToString = $LongToString.dechex($b);        
			
			$c = (int) ($Num & 0xFF0000) / 65536;
			
			If ($c < 16)
				$LongToString = $LongToString."0".dechex($c);
			Else
				$LongToString = $LongToString.dechex($c);
				
			If ($Num < 0)
				$d = ((int)($Num & 0x7F000000) / 16777216) | 0x80;
			Else
				$d = (int) ($Num & 0xFF000000) / 16777216;
						
			If ($d < 16)
				$LongToString = $LongToString."0".dechex($d);
			Else
				$LongToString = $LongToString.dechex($d);
			
			return $LongToString;
		}
				
		/**
		* Initialize the class
		*   This must be called before a digest calculation is started
		*/
		private function _MD5Init() {
		    $this->byteCounter = 0;
		    $this->state[1] = 0x67452301;
		    $this->state[2] = 0xefcdab89;
		    $this->state[3] = 0x98badcfe;
		    $this->state[4] = 0x10325476;		       
		}		
		
		/**
		* MD5 Transform
		**/
		private function _MD5Transform($Buffer) {
		    $a = $this->state[1];
		    $b = $this->state[2];
		    $c = $this->state[3];
		    $d = $this->state[4];
		    
		    // Round 1
		    $x = $Buffer;
		    
		    $a =$this->_FF( $a, $b, $c, $d, $x[0], S11, 0xd76aa478); /* 1 */
			$d =$this->_FF( $d, $a, $b, $c, $x[1], S12, 0xe8c7b756); /* 2 */
			$c =$this->_FF( $c, $d, $a, $b, $x[2], S13, 0x242070db); /* 3 */
			$b =$this->_FF( $b, $c, $d, $a, $x[3], S14, 0xc1bdceee); /* 4 */
			$a =$this->_FF( $a, $b, $c, $d, $x[4], S11, 0xf57c0faf); /* 5 */
			$d =$this->_FF( $d, $a, $b, $c, $x[5], S12, 0x4787c62a); /* 6 */
			$c =$this->_FF( $c, $d, $a, $b, $x[6], S13, 0xa8304613); /* 7 */
			$b =$this->_FF( $b, $c, $d, $a, $x[7], S14, 0xfd469501); /* 8 */
			$a =$this->_FF( $a, $b, $c, $d, $x[8], S11, 0x698098d8); /* 9 */
			$d =$this->_FF( $d, $a, $b, $c, $x[9], S12, 0x8b44f7af); /* 10 */
			$c =$this->_FF( $c, $d, $a, $b, $x[10], S13, 0xffff5bb1); /* 11 */
			$b =$this->_FF( $b, $c, $d, $a, $x[11], S14, 0x895cd7be); /* 12 */
			$a =$this->_FF( $a, $b, $c, $d, $x[12], S11, 0x6b901122); /* 13 */
			$d =$this->_FF( $d, $a, $b, $c, $x[13], S12, 0xfd987193); /* 14 */
			$c =$this->_FF( $c, $d, $a, $b, $x[14], S13, 0xa679438e); /* 15 */
			$b =$this->_FF( $b, $c, $d, $a, $x[15], S14, 0x49b40821); /* 16 */
			
			// Round 2
			$a =$this->_GG( $a, $b, $c, $d, $x[1], S21, 0xf61e2562); /* 17 */
			$d =$this->_GG( $d, $a, $b, $c, $x[6], S22, 0xc040b340); /* 18 */
			$c =$this->_GG( $c, $d, $a, $b, $x[11], S23, 0x265e5a51); /* 19 */
			$b =$this->_GG( $b, $c, $d, $a, $x[0], S24, 0xe9b6c7aa); /* 20 */
			$a =$this->_GG( $a, $b, $c, $d, $x[5], S21, 0xd62f105d); /* 21 */
			$d =$this->_GG( $d, $a, $b, $c, $x[10], S22,  0x2441453); /* 22 */
			$c =$this->_GG( $c, $d, $a, $b, $x[15], S23, 0xd8a1e681); /* 23 */
			$b =$this->_GG( $b, $c, $d, $a, $x[4], S24, 0xe7d3fbc8); /* 24 */
			$a =$this->_GG( $a, $b, $c, $d, $x[9], S21, 0x21e1cde6); /* 25 */
			$d =$this->_GG( $d, $a, $b, $c, $x[14], S22, 0xc33707d6); /* 26 */
			$c =$this->_GG( $c, $d, $a, $b, $x[3], S23, 0xf4d50d87); /* 27 */
			$b =$this->_GG( $b, $c, $d, $a, $x[8], S24, 0x455a14ed); /* 28 */
			$a =$this->_GG( $a, $b, $c, $d, $x[13], S21, 0xa9e3e905); /* 29 */
			$d =$this->_GG( $d, $a, $b, $c, $x[2], S22, 0xfcefa3f8); /* 30 */
			$c =$this->_GG( $c, $d, $a, $b, $x[7], S23, 0x676f02d9); /* 31 */
			$b =$this->_GG( $b, $c, $d, $a, $x[12], S24, 0x8d2a4c8a); /* 32 */
			
			// Round 3
			$a =$this->_HH( $a, $b, $c, $d, $x[5], S31, 0xfffa3942); /* 33 */
			$d =$this->_HH( $d, $a, $b, $c, $x[8], S32, 0x8771f681); /* 34 */
			$c =$this->_HH( $c, $d, $a, $b, $x[11], S33, 0x6d9d6122); /* 35 */
			$b =$this->_HH( $b, $c, $d, $a, $x[14], S34, 0xfde5380c); /* 36 */
			$a =$this->_HH( $a, $b, $c, $d, $x[1], S31, 0xa4beea44); /* 37 */
			$d =$this->_HH( $d, $a, $b, $c, $x[4], S32, 0x4bdecfa9); /* 38 */
			$c =$this->_HH( $c, $d, $a, $b, $x[7], S33, 0xf6bb4b60); /* 39 */
			$b =$this->_HH( $b, $c, $d, $a, $x[10], S34, 0xbebfbc70); /* 40 */
			$a =$this->_HH( $a, $b, $c, $d, $x[13], S31, 0x289b7ec6); /* 41 */
			$d =$this->_HH( $d, $a, $b, $c, $x[0], S32, 0xeaa127fa); /* 42 */
			$c =$this->_HH( $c, $d, $a, $b, $x[3], S33, 0xd4ef3085); /* 43 */
			$b =$this->_HH( $b, $c, $d, $a, $x[6], S34,  0x4881d05); /* 44 */
			$a =$this->_HH( $a, $b, $c, $d, $x[9], S31, 0xd9d4d039); /* 45 */
			$d =$this->_HH( $d, $a, $b, $c, $x[12], S32, 0xe6db99e5); /* 46 */
			$c =$this->_HH( $c, $d, $a, $b, $x[15], S33, 0x1fa27cf8); /* 47 */
			$b =$this->_HH( $b, $c, $d, $a, $x[2], S34, 0xc4ac5665); /* 48 */
			
			// Round 4
			$a =$this->_II( $a, $b, $c, $d, $x[0], S41, 0xf4292244); /* 49 */
			$d =$this->_II( $d, $a, $b, $c, $x[7], S42, 0x432aff97); /* 50 */
			$c =$this->_II( $c, $d, $a, $b, $x[14], S43, 0xab9423a7); /* 51 */
			$b =$this->_II( $b, $c, $d, $a, $x[5], S44, 0xfc93a039); /* 52 */
			$a =$this->_II( $a, $b, $c, $d, $x[12], S41, 0x655b59c3); /* 53 */
			$d =$this->_II( $d, $a, $b, $c, $x[3], S42, 0x8f0ccc92); /* 54 */
			$c =$this->_II( $c, $d, $a, $b, $x[10], S43, 0xffeff47d); /* 55 */
			$b =$this->_II( $b, $c, $d, $a, $x[1], S44, 0x85845dd1); /* 56 */
			$a =$this->_II( $a, $b, $c, $d, $x[8], S41, 0x6fa87e4f); /* 57 */
			$d =$this->_II( $d, $a, $b, $c, $x[15], S42, 0xfe2ce6e0); /* 58 */
			$c =$this->_II( $c, $d, $a, $b, $x[6], S43, 0xa3014314); /* 59 */
			$b =$this->_II( $b, $c, $d, $a, $x[13], S44, 0x4e0811a1); /* 60 */
			$a =$this->_II( $a, $b, $c, $d, $x[4], S41, 0xf7537e82); /* 61 */
			$d =$this->_II( $d, $a, $b, $c, $x[11], S42, 0xbd3af235); /* 62 */
			$c =$this->_II( $c, $d, $a, $b, $x[2], S43, 0x2ad7d2bb); /* 63 */
			$b =$this->_II( $b, $c, $d, $a, $x[9], S44, 0xeb86d391); /* 64 */
								
		    $this->state[1] = $this->_add($a,$this->state[1]);
		    $this->state[2] = $this->_add($b,$this->state[2]);
		    $this->state[3] = $this->_add($c,$this->state[3]);
		    $this->state[4] = $this->_add($d,$this->state[4]);
		}
		
		private function _LongLeftRotate($value, $bits) {
		    $bits = $bits%32;		    
		    If ($bits == 0)
		    	return $value;
		    	
			 For ($lngI = 1;$lngI < $bits +1;$lngI++) {
		        $lngSign = $value & 0xC0000000;
		        $value = ($value & 0x3FFFFFFF) * 2;	
		        $value = $value | ($this->_boolhex($lngSign < 0) & 1) | ($this->_boolhex($lngSign & 0x40000000) & 0x80000000);		          
		    }		    
		    return $value;
		}
		
		private function _add($x, $y) {
			return (($x&0x7FFFFFFF) + ($y&0x7FFFFFFF)) ^ ($x&0x80000000) ^ ($y&0x80000000);
		}
				
		private function _boolhex($value)  {
			if ($value)
				return 0xFFFFFFFF;
			else
				return 0x00000000;
		}
		
		private function _FF($a,$b,$c,$d,$x,$s,$ac) {
			$F =  ($b & $c) | ((~$b) & $d);			
			$q = $this->_add($this->_add($a,$F), $this->_add($x, $ac));			
			$q = $this->_LongLeftRotate($q,$s);			
			$value = $this->_add($q, $b);
			return $value;
		}
		
		private function _GG($a,$b,$c,$d,$x,$s, $ac) {
			$G = ($b & $d) | ($c & (~$d));
			$q = $this->_add($this->_add($a,$G), $this->_add($x, $ac));
			$q = $this->_LongLeftRotate($q,$s);	
			$value = $this->_add($q, $b);			
			return $value;
		}
		
		private function _HH($a,$b,$c,$d,$x,$s,$ac) {
			$H = $b ^ $c ^ $d;
			$q = $this->_add($this->_add($a, $H), $this->_add($x, $ac));			
			$q = $this->_LongLeftRotate($q,$s);
			$value = $this->_add($q, $b);			
			return $value;
		}
		
		private function _II($a,$b,$c,$d,$x,$s,$ac) {
			$I = $c ^ ($b | (~$d));
		    $q = $this->_add($this->_add($a, $I), $this->_add($x, $ac));
		    $q = $this->_LongLeftRotate($q,$s);			
			$value = $this->_add($q, $b);		
			return $value;
		}	
	}
?>