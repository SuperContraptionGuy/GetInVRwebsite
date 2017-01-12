<?php
// header("Refresh: 3; url= $url");
ob_start(); // ensures anything dumped out will be caught

//		For when debugging is done:

// ob_start(); // ensures anything dumped out will be caught

// // do stuff here
// $url = 'http://example.com/thankyou.php'; // this can be set based on whatever

// // clear out the output buffer
// while (ob_get_status()) 
// {
//     ob_end_clean();
// }

// // no redirect
// header( "Location: $url" );





include_once("_phpBackend/sqldbinfo/info.php");
	include_once("_phpBackend/mailer.php");

	function populateArray($queryobj) {

		$array = array();

		for($i  = 0; $i < $queryobj->num_rows; $i++) {
			mysqli_data_seek ($queryobj, $i);
			$row = mysqli_fetch_array($queryobj, MYSQLI_ASSOC);
			//for($j = 0; $j < count($row); $j++) {
				//$reservations[i] = $row["Name"];
			//array_push($array, $row["First Name"] . ', ' . $row["Timestamp"] . '<br>');

			$rowString = '<table><tr><th>Key</th><th>Value</th></tr>';

			foreach ($row as $key => $value) {
				
				$rowString .= "<tr><td>$key:</td><td>$value</td></tr>";
			}

			$rowString .= '</table>';

			array_push($array, $rowString);

				//print $row["Name"] . ', ' . $row["Appointment"] . '<br>';
			//}
		}
		return $array;
	}
	function extractArray($inputArray) {

		$outputArray = array();
		print 'processing input array...<br>';
		foreach($inputArray as &$value) {
			print gettype($value) . ': ' . $value . '<br>';
			if(gettype($value) == 'array') {

				print 'got array<br>';
				$gotArray = extractArray($value);
				foreach ($gotArray as &$value2) {
					array_push($outputArray, $value2);
				}

			} else {
				array_push($outputArray, $value);
			}
		}

		return $outputArray;
	}

	// Clean results and check for errors   NEEDS TO BE MADE MORE SECURE
	ini_set('display_errors', 1);
	error_reporting(E_ALL & ~ E_NOTICE);

	// Define the variables
	//$responses = extractArray($_POST);
	$responses = $_POST;
	// Redirect URL after processing form inputs
	$url = 'index.php';
	
	// Set action/db to use
	$action = array_pop($responses);
	// print 'action sent: ' . $action . '<br>';

	// Check for errors if the form
	$error = FALSE;
	foreach($responses as $key => &$value) {
		if($value == '') {

			$error = TRUE;

			$value = "'" . $value . "'";
			// print "Error. Recieved value: " . $value . "<br>";
		} else {
			$value = "'" . $value . "'";
			$key = "'" . $key . "'";
			// print "Sucessfully recieved key/value pair: $key: $value<br>";
		}
	}

	// Process input if no errors
	if(!$error) {

		// Insert form data to database
		// print "<br>no errors<br>";
		$table = '';
		if($action == 'reservation') {

			$table = "`Appointment Requests`";
			// $url = 'reservations.html';
		} else if($action == 'contact') {

			$table = "`ContactForm`";
			// $url = 'contact.html';
		} else if($action == 'newsletter') {

			$table = "`Email List`";
			// $url = 'index.html';
		} else {
			$table = "`none`";
		}
		// print 'table selected: ' . $table . '<br>';
		
		$values = implode(", ", array_values($responses));
		$keysArray = array_keys($responses);
		// foreach ($keysArray as &$value) {
		// 	$value = "'" . $value . "'";
		// }
		$keys = implode(", ", array_values($keysArray));
		$query = "INSERT INTO $table ($keys, timeStamp) VALUES ($values, now())";
		//$query = "INSERT INTO TestForms VALUES ('hudson', '2016-12-31', 'now', CURRENT_TIMESTAMP)";
		//$q = mysql_query($query)
;
		// Check for db errors:
		if(mysqli_query($conn, $query)) {

			// echo "<br>SQL insert sucessuful<br>";
		} else {
			// echo "<br>MySQL Insertion Failed. Error: " . $query, "<br>" . mysqli_error($conn), "<br>";
		}



		// Compile info for mailer and send to slaves
		$returnobj = mysqli_query($conn, "SELECT * FROM `Appointment Requests` WHERE date >= curdate();");
		$reservations = populateArray($returnobj);

		$returnobj = mysqli_query($conn, "SELECT * FROM `ContactForm` WHERE (date>=(SELECT `Update Emails`.date FROM `Update Emails` ORDER BY date DESC LIMIT 1));");
		$contacts = populateArray($returnobj);

		$returnobj = mysqli_query($conn, "SELECT * FROM `Email List` WHERE (date>=(SELECT `Update Emails`.date FROM `Update Emails` ORDER BY date DESC LIMIT 1));");
		$newsletters = populateArray($returnobj);
		
		// CAN'T SEND EMAIL BECAUSE OF HOW STUPID OUR MAIL SYSTEM IS.  I OFFICIALLY HATE EMAIL.
		mailSlaves($reservations, $contacts, $newsletters);

		$query = "INSERT INTO `Update Emails` VALUES (curdate(), now());";
		if(mysqli_query($conn, $query)) {

			// echo "update email db entry sucessuful.<br>";
		} else {

			// echo "update email db entry failed.<br>";
		}

		// feedback for user
		// print 'Input recieved:<br>';
		// foreach($responses as &$value) {

		// 	print $value . '<br>';
		// }
		print '<input type="submit" value="back" <a href="#" onclick="history.back();"></a>';
	}

	

	mysqli_close($conn);


if(!$error) {
	$url .= '?submited=true'; // this can be set based on whatever
} else {
	$url .= '?submited=false';
}

// clear out the output buffer
// while (ob_get_status()) 
// {
//     ob_end_clean();
// }

//sleep(5);
// no redirect
// header( "Location: $url" );
header("Refresh: 3; url= $url");

ob_end_flush();


?>
<?php
// header("Refresh: 3; url= $url");
// ob_start(); // ensures anything dumped out will be caught

?>

<!DOCTYPE html>
<html>



<style type="text/css" src="_css/landing-page.css"></style>


<t1 style="font-size: 20pt"> Your information has been recorded.  Thank you.</t1>



</html>