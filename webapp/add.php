<?php
//executes if the request metod equals "POST"
if ($_SERVER['REQUEST_METHOD'] == "POST") {
	$listItem = $_POST['listItem'];
	//checks if the key is in the array; if so, $complete is raised to 1
	if (array_key_exists('fin', $_POST)) {
		$complete = 1;
	//if not, $complete equals 0
	} else {
		$complete = 0;
	}

	//checks if the POST value is empty
	if (empty($_POST['finBy'])) {
		$finBy = null;
	//if it is not, then POST = finBy
	} else {
		$finBy = $_POST['finBy'];
	}
	
	$url="http://3.230.84.0/api/task.php";
			
	$data = array('completed'=>$complete,'taskName'=>$listItem,'taskDate'=>$finBy);
	$data_json = json_encode($data);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_json)));
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	//body of response
	$response = curl_exec($ch); 
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
			
	//staus code 204
	if($httpcode==201){
		header("Location: index.php");
	}else{
		header("Location: index.php?error=add");
	}
}	
