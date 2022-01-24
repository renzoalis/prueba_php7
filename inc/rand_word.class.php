<?php
	class rand_word
	{
	    var $vowels = array('a','e','i','o','u','y');
	    var $consonants = array('b','c','d','f','g','h','j','k','l','m','n','p','r','s','t','v','w','z','ch','qu','th','xy');
	    var $word = '';
	
	    function rand_word($length = 5, $lower_case = true, $ucfirst = false, $upper_case = false)
	    {
	        $done = false;
	        $const_or_vowel = 1;
	        
	        while (!$done)
	        {
	            switch ($const_or_vowel)
	            {
	                case 1:
	                    $this->word .= $this->consonants[array_rand($this->consonants)];
	                    $const_or_vowel = 2;
	                    break;
	                case 2:
	                    $this->word .= $this->vowels[array_rand($this->vowels)];
	                    $const_or_vowel = 1;
	                    break;
	            }
	             
	            if (strlen($this->word) >= $length)
	            {
	                $done = true;
	            }
	        }
	
	        $this->word = substr($this->word, 0, $length);
	        $this->word = ($lower_case) ? strtolower($this->word) : $this->word;
	        $this->word = ($ucfirst) ? ucfirst(strtolower($this->word)) : $this->word;
	        $this->word = ($upper_case) ? strtoupper($this->word) : $this->word;
	        
	        return $this->word;
	    }
	}
?>