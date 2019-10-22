<?php
//GET
if($_SERVER['REQUEST_METHOD'] == "GET") {
	
	$listID = $_GET['listID'];		
	try {
		$sql = "SELECT * FROM doList";
		$stmt = $dbh->prepare($sql);
		$response = $stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
	http_response_code(405);//method not allowed
	echo "Unsupported HTTP method";
	exit();
}