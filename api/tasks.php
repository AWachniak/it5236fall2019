<?php
//GET
if($_SERVER['REQUEST_METHOD'] == "GET") {
	if(array_key_exists('listID',$_GET)){
		$listID = $_GET['listID'];
	}else{
		http_response_code(400);
		echo "Missing listID";
		exit(); 
	}
	$listID = $_GET['listID'];
	if (!$dbconnecterror) {
		
		try {
			$sql = "SELECT * FROM doList WHERE listID=:listID";
			$stmt = $dbh->prepare($sql);
			$stmt->bindParam(":listID", $listID);
			$response = $stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			if (!is_array($result)){
				http_response_code(404);
				exit();
			}
			
			http_response_code(200);
			echo json_encode ($result);
			exit();

		} catch (PDOException $e) {
			http_response_code(504);
			echo "Database exception";
			exit();

		}
	} else {
		http_response_code(504);
	}
} else {
	http_response_code(405);//method not allowed
	echo "Unsupported HTTP method";
	exit();
}