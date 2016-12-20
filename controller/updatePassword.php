<?php session_start();
    
	include "../includes/config.php";
    include "../includes/functions.php";

	$user_token = $_SESSION["key"];
	$userName = $_SESSION["userName"];
	
	$url = "http://127.0.0.1:8000/rest-auth/password/change/?format=json";
	
	$new_password1=$_POST["new_password1"];
	$new_password2=$_POST["new_password2"];
	
	$jsonUser["new_password1"] = $new_password1;
	$jsonUser["new_password2"] = $new_password2;
	
	$response = requestApi($user_token,$url,$jsonUser,"post");
	 
?>