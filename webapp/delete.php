<?php
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