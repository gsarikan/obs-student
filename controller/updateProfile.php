<?php session_start();
    
	include "../includes/config.php";
        include "../includes/functions.php";

	$user_token = $_SESSION["key"];
	$userName = $_SESSION["userName"];
	
	$student_id = $_POST["student_id"]; 
        $user_id = $_POST["user_id"]; 
	
	$user_url = "http://127.0.0.1:8000/users/".$user_id."/?format=json";
	$student_url = "http://127.0.0.1:8000/students/".$student_id."/?format=json";
	
    	$email = $_POST["email"];
    	$phone = $_POST["phone"]; 
	
    	$jsonUser["email"]= $email; 
    	$jsonUser["username"]= $userName; 
    	$jsonUser["id"]= $user_id; 
	
    	$jsonStudent["phone"]= $phone ; 
    	$jsonStudent["id"]= $student_id ;  
    	$jsonStudent["user"]= $user_id ;   
	 	
	$userResponse = requestApi($user_token,$user_url,$jsonUser,"put");
	$studentResponse = requestApi($user_token,$student_url,$jsonStudent,"put");
		
?>
