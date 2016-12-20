<?php

	function getApi($token,$url)
	{
		$response = \Httpful\Request::get($url)
		->addHeaders(array(
			'Authorization' => 'Token '.$token,
			'Content-Type' => 'application/json'))
		->expectsJson()
		->send();
		return json_decode($response,true);
	}

	function requestApi($token,$url,$dizi,$method)
	{
		$response = \Httpful\Request::$method($url)
		->addHeaders(array(
            'Authorization' => 'Token '.$token,            
            'Content-Type' => 'application/json'
		))
		->body(json_encode($dizi))
		->sendsJson()
		->send();
		return json_decode($response,true);
	}
	
?>