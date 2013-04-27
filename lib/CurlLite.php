<?php

class CurlLite{
	protected $options = array();
	protected $channel;
	protected $current_url;
	const GITHUB_DEFAULT_URL = 'https://api.github.com';
    

    public function __construct($custom_url = self::GITHUB_DEFAULT_URL){
    		/*
		$this->options = array(
			CURLOPT_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS,
			CURLOPT_REDIR_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS,
		);
		*/
		$curl = curl_init($custom_url);
		$this->current_url = $custom_url;
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_USERPWD, 'login:password');
		curl_setopt($curl, CURLOPT_COOKIEJAR, "my_cookies.txt");
		curl_setopt($curl, CURLOPT_COOKIEFILE, "my_cookies.txt");
		curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		
		$this->channel = $curl;
        
    }
	
	public function __destruct(){
		curl_close($this->channel);
	}
    
	public function setopt($option, $value){
		return curl_setopt($this->channel, $option, $value);
	}
	
	public function execRequest(){
		return curl_exec($this->channel);
	}
	
	public function prepareRequest($method = "GET", array $data = array()){
		$meth = strtoupper($method);
		$request_string = "";
		$first_item = true;
		foreach($data as $k => $v){
			if($first_item){
				$request_string .= "$k=$v";
				$first_item = false;
			}else{
				$request_string .= '&' . "$k=$v";
			}
		}
		//$request_string = rawurlencode($request_string);
		if($meth == "POST"){
			$this->setopt(CURLOPT_POST, 1);
			$this->setopt(CURLOPT_POSTFIELDS, $request_string);
			
		}else{
			$this->setopt(CURLOPT_POST, 0);
			$req_url = $this->current_url . '?' . $request_string;
			$this->setopt(CURLOPT_URL, $req_url);
			$this->current_url = $req_url;
		}
	}
}
?>
