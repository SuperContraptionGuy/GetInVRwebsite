<?php

//	Altervista host
define('DB_USER', 'you2industries');
define('DB_PASSWORD', '');

//	ContraptionGames host
// define('DB_USER', 'root');
// define('DB_PASSWORD', 'contraptions');

define('DB_HOST', 'localhost');
define('DB_NAME', "my_you2industries");

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if(!$conn) {
	die("<br>Connection failed: " . mysqli_connect_error($conn));
}
// echo "<br>Connection successful.<br>";

$selected = mysqli_select_db($conn, DB_NAME);
if($selected) {
	// echo "<br>DB Select " . DB_NAME . " successful.<br>";
} else {
	// echo "<br>DB Select $selected Failure Error: " . mysqli_error($conn), "<br>";
}

//$dbc = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('Failure: ' . mysql_error());
//mysql_select_db(DB_NAME) or die('Cound not select database: ' . mysql_error());


?>