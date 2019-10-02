<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$listID = $_POST['listID'];
	
	if (array_key_exists('fin', $_POST)) {
		$complete = 1;
	} else { 
		$complete = 0;
	if empty ($_POST['finBy'])) {
	} else {
		$finBy = $_POST['finBy'];
	}$listItem = $_POST['listItem'];
	
	if (!$dbconnecterror) {
		try{
			//call to get api
			// api url 
			$url="http://3.230.84.0/api/task.php?listID=$listID";
			
			$data = array('completed'=>'$complete','taskName'=>'$finBy','taskDate'=>$finBy);
			$data_json = json_encode($data);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_json)));
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
			curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			//body of response
			$response = curl_exec($ch); 
			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$response  = curl_exec($ch);
			curl_close($ch);
			
			//staus code 204
			if($httpcode=='204'){
				header("Location: index.php");
			} else {
				header("Location: index.php?error=edit");
			}
		
	
}
?>
