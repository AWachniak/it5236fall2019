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

//ends the try unless the database is still connected 
}catch(Exception $e){
	$dbconnecterror = TRUE;
}

//executes if the request metod equals "POST"
if ($_SERVER['REQUEST_METHOD'] == "POST") {
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
	if (!$dbconnecterror) {
		try {
			//inserts the values into the database
			$sql = "INSERT INTO doList (complete, listItem, finishDate) VALUES (:complete, :listItem, :finishDate)";
			//sets the prepared statement 
			$stmt = $dbh->prepare($sql);		
			//binds the value for the sql statement
			$stmt->bindParam(":complete", $complete);
			//binds the value for the sql statement
			$stmt->bindParam(":listItem", $_POST['listItem']);
			//binds the value for the sql statement
			$stmt->bindParam(":finishDate", $finBy);
			//executes the sql statements
			$response = $stmt->execute();	
			
			//sends a raw HTTP header
			header("Location: index.php");
		//ends unless the header does not send
		} catch (PDOException $e) {
			header("Location: index.php?error=add");
		}	
	//if it fails, reports error
	} else {
		header("Location: index.php?error=add");
	}
}


?>
