
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Declare the credentials to the database
$dbconnecterror = FALSE;
$dbh = NULL;
require_once 'credentials.php';
try{

	$conn_string = "mysql:host=".$dbserver.";dbname=".$db;

	$dbh= new PDO($conn_string, $dbusername, $dbpassword);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}catch(Exception $e){
	//database issues were encountered
	http_response_code(504);
	echo "Databsae timeout";
	exit();
}

if ($_SERVER['REQUEST_METHOD'] == "PUT") {
	if(array_key_exists('listID',$_GET)){
		$listID = $_GET['listID'];
	}else{
		http_response_code(400);
		echo "Missing listID";
		exit(); 
	}
	$listID = $_GET['listID'];
	//decode the json body from the request
	$task = json_decode(file_get_contents('php://input'), true);
	if (array_key_exists('completed', $task)) {
		$complete = $task["completed"];
	} else {
		http_response_code(400);
		echo "Missing completed status";
		exit();
	}
	if (array_key_exists('taskName', $task)) {
		$taskName = $task["taskName"];
	} else {
		http_response_code(400);
		echo "Missing taskName";
		exit();
	}
	if (array_key_exists('taskDate', $task)) {
		$taskDate = $task["taskDate"];
	} else {
		http_response_code(400);
		echo "Missing taskDate";
		exit();
	}
	if (!$dbconnecterror) {
		try {
			$sql = "UPDATE doList SET complete=:complete, listItem=:listItem, finishDate=:finishDate WHERE listID=:listID";
			$stmt = $dbh->prepare($sql);
			$stmt->bindParam(":complete", $complete);
			$stmt->bindParam(":listItem", $taskName);
			$stmt->bindParam(":finishDate", $taskDate);
			$stmt->bindParam(":listID", $listID);
			$response = $stmt->execute();
			http_response_code(204);
			
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
	echo "Expected PUT";
	exit();
	//put
}
?>
