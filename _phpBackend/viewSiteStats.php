<style>
body {background-color: white;}
h1   {color: black;}
p    {color: black;}
th, td {
	border: 1px solid black;
}
</style>

<canvas id="chart" width="400" height="400"></canvas>

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


$chartData = array();






echo "<p>Database dump:</p>";
echo "<table>"; // start a table tag in the HTML
echo "<tr><th colspan='3'>The user interactions log</th></tr>";
echo "<tr><th>SessionID</th><th>Activity</th><th>Session Start Time</th></tr>";

while($row = mysqli_fetch_array($result)){   //Creates a loop to loop through results
	echo "<tr><td>" . $row['SessionID'] . "</td><td>";  //$row['index'] the index here is a field name

	$data = json_decode($row['path']);

	// Open the table
	echo "<table>";
	echo "<tr><th>Page Name</th><th>Page Variant</th><th>TimeVisited</th><th>Elapsed Time</th><th>Actions Taken</th></tr>";


	// Cycle through the array
	foreach ($data as $idx => $page) {

	    // Output a row
	    echo "<tr>";
	    echo "<td>$page->page</td>";
	    echo "<td>$page->variant</td>";
	    echo "<td>$page->timeStamp</td>";
	    echo "<td>$page->elapsedTimeSec</td>";
	    echo "<td>";

	    if($page->page == 'index') {

		    $dateObj = new DateTime($page->timeStamp);
		    $date = $dateObj->format('m-d');

		    if(isset($chartData[$date])) {

		    	$chartData[$date] += 1;
		    } else {

		    	$chartData[$date] = 1;
		    }

		    //var_dump($chartData);

		}
	    

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


//	make chart
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
<script>


var ctx = document.getElementById('chart');
var myChart = new Chart(ctx, {
	type: 'line',
	data: {
		labels: [ <?php echo "'" . implode("', '", array_reverse(array_keys($chartData))) . "'"; ?> ],
		datasets: [{

			label: 'Page Loads',
			data: [ <?php echo implode(', ', array_reverse($chartData)); ?> ],
			backgroundColor: [ 'rgba(255, 102, 1, 1)'],
			borderColor: ['rgba(255, 102, 1, 0.2)']
		}]
	}
});

</script>



<canvas id="myChart" width="400" height="400"></canvas>
<script>
var ctx = document.getElementById("myChart");
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
        datasets: [{
            label: '# of Votes',
            data: [12, 19, 3, 5, 2, 3],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});
</script>
