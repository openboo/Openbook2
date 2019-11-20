<?php
	/** URL Library **/
	/* Convert a string so that all urls are turned into pre-formatted links.*/
	function url_links($string){
		$image_url = '/(^| ){{\s*+(.+?)}}/i';
		$string = preg_replace($image_url, '$1<img style="max-width:100%" src="$2" />', $string);

		$url = '/(^| )((https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/i';
		$string = preg_replace($url, '$1<a href="$2" target="_blank" title="$2">$2</a>', $string);

		//'~<br/>~'
		//$end = '~(?!<br/>\z)~';
		//$string = preg_replace($end, 'tagged: $1', $string);
		/*$substring = substr($string,-5);
		echo ":::";
		echo $substring;
		echo ":::";*/


		return $string;
	}



	function mark_tagged($string){
		$substring = substr($string,-5);
		if($substring!='<br/>'){
			$string_arr = explode("<br/>",trim($string));
			$lastline = $string_arr[count($string_arr)-1];

			$string = str_replace($lastline,"tagged: ".$lastline,$string);
		}
		return $string;
	}



?>
