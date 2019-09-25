<?php
//sets the configuration option value

ini_set('display_errors', 1);
//sets the configuration option value
ini_set('display_startup_errors', 1);
//reports all errors
error_reporting(E_ALL);



// Declare the credentials to the database
$dbconnecterror = FALSE;
$dbh = NULL;

//requires the file to be presented once
require_once 'credentials.php';

try{
	//establishes the database connection
	$conn_string = "mysql:host=".$dbserver.";dbname=".$db;
	
	//uses specific credentials
	$dbh= new PDO($conn_string, $dbusername, $dbpassword);
	// if there is an error in SQL, PDO will throw exceptions and script will stop running
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//ends unless there is an error
}catch(Exception $e){
	$dbconnecterror = TRUE;
}
//if posting
if ($_SERVER['REQUEST_METHOD'] == "POST") {
	
	//sets the listID
	$listID = $_POST['listID'];
	
	//if a given key is provided, sets $complete to 1
	if (array_key_exists('fin', $_POST)) {
		$complete = 1;
	//if not, sets $complete to 0
	} else {
		$complete = 0;
	}
	//checks if the POST value is empty, if not sets $finBy to null
	if (empty($_POST['finBy'])) {
		$finBy = null;
	} else {
		$finBy = $_POST['finBy'];
	}
	//sets the listitem
	$listItem = $_POST['listItem'];
	
	
	//if there is an error
	if (!$dbconnecterror) {
		try {
			//updates sql valaues
			$sql = "UPDATE doList SET complete=:complete, listItem=:listItem, finishDate=:finishDate WHERE listID=:listID";
			//executes prepared sql statement
			$stmt = $dbh->prepare($sql);		
			//binds the value for the sql statement
			$stmt->bindParam(":complete", $complete);
			//binds the value for the sql statement
			$stmt->bindParam(":listItem", $listItem);
			//binds the value for the sql statement
			$stmt->bindParam(":finishDate", $finBy);
			//binds the value for the sql statement
			$stmt->bindParam(":listID", $listID);

			//executes the sql statements
			$response = $stmt->execute();	
			
			//sends a raw http header
			header("Location: index.php");
			
		//ends unless the header does not send, sends this header
		} catch (PDOException $e) {
			header("Location: index.php?error=edit");
			
		}
	//otherwise, sends this header
	} else {
		header("Location: index.php?error=edit");
	}
}
?>
