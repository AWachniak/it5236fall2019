<?php
//sets the configuration option value
ini_set('display_errors', 1);
//sets the configuration option value
ini_set('display_startup_errors', 1);
//reports all errors
error_reporting(E_ALL);

//not connected
$dbconnecterror = FALSE;
//not database
$dbh = NULL;
//no error reporting
$dbReadError = FALSE;

//requires specific file to be presented once
require_once 'credentials.php';

try{

	//connnects to specific database
	$conn_string = "mysql:host=".$dbserver.";dbname=".$db;
	
	//uses specific credentials
	$dbh= new PDO($conn_string, $dbusername, $dbpassword);
	// if there is an error in SQL, PDO will throw exceptions and script will stop running
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	//ends the try unless the database is still connected 
}catch(Exception $e){
	$dbconnecterror = TRUE;
}

//if there is a connection error
if (!$dbconnecterror) {
	
	try {
		//selects from list
		$sql = "SELECT * FROM doList";
		//sets prepared execute statement
		$stmt = $dbh->prepare($sql);
		//executes statement
		$stmt->execute();
		//retrieves everthing
		$result = $stmt->fetchAll();
	//ends unless the header does not send
	} catch (PDOException $e) {
		//sets error results to TRUE
		$dbReadError = TRUE;
	}

}
?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="description" content="doIT">
		<meta name="author" content="Russell Thackston">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title>doIT</title>

		<link rel="stylesheet" href="style/style.css">
		
		<link href="https://fonts.googleapis.com/css?family=Chilanka%7COrbitron&display=swap" rel="stylesheet">		

	</head>


	<body>
		<a href="index.php"><h1 id="siteName">doIT</h1></a>
		<hr>

			<?php if(isset($result)) { //executes if there are results
		?>
				<?php foreach($result as $item){ //lists each result as a separate item
		?>
					<div class="list">
						<form method="POST" action="edit.php" style="display: inline-block">
							<input type="hidden" 	name="listID" value="<?php echo $item["listID"];?>" >
							<input type="checkbox"	name="fin" <?php if($item["complete"]=='1'){echo "checked='checked'";} ?> >
							<input type="text" 	name="listItem" size="50" value="<?php echo $item["listItem"];?>" maxlength="100" >
							<span>by:</span>
							<input type="date" 	name="finBy" value="<?php if($item['finishDate']=='0000-00-00'){echo '';} else {echo $item['finishDate'];} ?>" >
							<input type="submit" 	name="submitEdit" value="&check;" >
						</form>
						<form method="POST" action="delete.php" style="display: inline-block">
							<input type="hidden" name="listID" value="<?php echo $item["listID"];//lists item
							       ?>" >
							<input type="submit" name="submitDelete" value="&times;" >
						</form>
					</div>
					<?php } ?>
			<?php } ?>

			<div class="list">
				<form  method="POST" action="add.php">
					<input type="checkbox" name="fin" value="done">
					<input type="text" name="listItem" size="50">
					<span>by:</span>
					<input type="date" id="finDate" name="finBy">
					<input type="submit" value="&#43;">
				</form>
			</div>
			
			<?php if ($dbconnecterror) { //runs if there is a connection error
		?>
			<div class="error">
				Uh oh! There was an error connecting to the database. Please check your connection settings and try again.
			</div>
			<?php } ?>
			<?php if ($dbReadError) { //runs if there is a read error
		?>
			<div class="error">
				Uh oh! There was an error reading the to do list from the database. Please check your connection settings and try again.
			</div>
			<?php } ?>
			<?php if (array_key_exists('error', $_GET)) { //runs if it cant use the key
		?>
				<?php if ($_GET['error'] == 'add') { //runs if it cant add an item
		?>
				<div class="error">
					Uh oh! There was an error adding your to do item. Please try again later.
				</div>
				<?php } ?>
				<?php if ($_GET['error'] == 'delete') { //runs if it cant delete an item
		?>
				<div class="error">
					Uh oh! There was an error deleting your to do item. Please try again later.
				</div>
				<?php } ?>
				<?php if ($_GET['error'] == 'edit') { //runs if it cant edit an item
		?>
				<div class="error">
					Uh oh! There was an error updating your to do item. Please try again later.
				</div>
				<?php } ?>
			<?php } ?>
	</body>
</html>
