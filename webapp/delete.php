<?php
//sets the configuration option value
ini_set('display_errors', 1);
//sets the configuration option value
ini_set('display_startup_errors', 1);
//reports all errors
error_reporting(E_ALL);




//executes if posting
if ($_SERVER['REQUEST_METHOD'] == "POST") {
	$listID = $_POST['listID'];
	$url="http://3.230.84.0/api/task.php?listID=$listID";
			
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	//body of response
	$response = curl_exec($ch); 
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	
	if($httpcode==204){
			header("Location: index.php");
	}else{
			header("Location: index.php?error=delete");
	}
}