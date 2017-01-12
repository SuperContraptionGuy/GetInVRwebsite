<style>
body {background-color: powderblue;}
h1   {color: blue;}
p    {color: red;}
th, td {
	border: 1px solid black;
}
</style>
<?php

//	Error reporting:
ini_set('display_errors', 1);
ini_set('display_startup_error', 1);
error_reporting(E_ALL);

//	Get php server info
require("sqldbinfo/info.php");

//	Get the json file for the current user if it exisits
$query = "SELECT * from visitorStats ORDER BY SessionStartTime DESC;";
$result = mysqli_query($conn, $query);

echo "<table>"; // start a table tag in the HTML
echo "<tr><th colspan='3'>The user interactions log</th></tr>";
echo "<tr><th>SessionID</th><th>Activity</th><th>Session Start Time</th></tr>";

while($row = mysqli_fetch_array($result)){   //Creates a loop to loop through results
	echo "<tr><td>" . $row['SessionID'] . "</td><td>";  //$row['index'] the index here is a field name

	$data = json_decode($row['path']);

	// Open the table
	echo "<table>";
	echo "<tr><th>Page Name</th><th>Page Variant</th><th>TimeVisited</th><th>Actions Taken</th></tr>";

	// Cycle through the array
	foreach ($data as $idx => $page) {

	    // Output a row
	    echo "<tr>";
	    echo "<td>$page->page</td>";
	    echo "<td>$page->variant</td>";
	    echo "<td>$page->timeStamp</td>";
	    echo "<td>";

	    // Open the table
		echo "<table>";
		echo "<tr><th>Element ID Retrieved</th><th>TimeStamp</th></tr>";

		// Cycle through the array
		foreach ($page->actions as $idx => $action) {

		    // Output a row
		    echo "<tr>";
		    echo "<td>$action->elementID</td>";
		    echo "<td>$action->timeStamp</td>";
		    echo "</tr>";
		}

		// Close the table
		echo "</table>";

	    echo "</td>";
	    echo "</tr>";
	}

	// Close the table
	echo "</table>";

	echo "</td>";

	echo "<td>" . $row['SessionStartTime'] . "</td></tr>";
}

echo "</table>"; //Close the table in HTML

// var_dump($data);

mysqli_close($conn); //Make sure to close out the database connection




?>