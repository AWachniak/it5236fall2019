<?php
//sets the configuration option value
ini_set('display_errors', 1);
//sets the configuration option value 
ini_set('display_startup_errors', 1);
//sets all php errors to be recorded
error_reporting(E_ALL);

//sets variable to false
$dbconnecterror = FALSE;
//sets variable to null
$dbh = NULL;

//necessitates the file to be presented one time
require_once 'credentials.php';

//begins try
try{
	
	//sets variable to the database name and password
	$conn_string = "mysql:host=".$dbserver.";dbname=".$db;
	
	//sets variable to a new connection 
	$dbh= new PDO($conn_string, $dbusername, $dbpassword);
	// if there is an error in SQL, PDO will throw exceptions and script will stop running
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
}catch(Exception $e){
	$dbconnecterror = TRUE;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	
	if (array_key_exists('fin', $_POST)) {
		$complete = 1;
	} else {
		$complete = 0;
	}

	if (empty($_POST['finBy'])) {
		$finBy = null;
	} else {
		$finBy = $_POST['finBy'];
	}

	if (!$dbconnecterror) {
		try {
			$sql = "INSERT INTO doList (complete, listItem, finishDate) VALUES (:complete, :listItem, :finishDate)";
			$stmt = $dbh->prepare($sql);			
			$stmt->bindParam(":complete", $complete);
			$stmt->bindParam(":listItem", $_POST['listItem']);
			$stmt->bindParam(":finishDate", $finBy);
			$response = $stmt->execute();	
			
			header("Location: index.php");
			
		} catch (PDOException $e) {
			header("Location: index.php?error=add");
		}	
	} else {
		header("Location: index.php?error=add");
	}
}


?>
