
<canvas id="testing" width="400" height="400"></canvas>

<head>
	<link href="../_css/bootstrap.min.css" rel="stylesheet">
	<link href="../_css/sitestat.css" rel="stylesheet">

</head>



<div class="container">
	<h1><center>Site Viewership</center></h1>
		<div class="row">
			<div class="col-md-6">
				<canvas id="VisitorCounter"></canvas>
			</div>
			<div class="col-md-6">
					<canvas id="visitorsTotal"></canvas>
				</div>
		</div>
	</div>
</div>

<div class="container">
	<h1><center>Splash Page</center></h1>
		<div class="row">
			<div class="col-md-6">
				<canvas id="splashRetentionRate"></canvas>
			</div>
				<div class="col-md-6">
					<canvas id="splashSignUps"></canvas>
				</div>
		</div>
	</div>
</div>

<div class="container">
	<h1><center>More Information</center></h1>
		<div class="row">
			<div class="col-md-6">
				<canvas id="infoRetentionRate"></canvas>
			</div>
			<div class="row">
				<div class="col-md-6">
					<canvas id="infoSignUps"></canvas>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="container">
	<h1><center>Reinforcements</center></h1>
		<div class="row">
			<div class="col-md-6">
				<canvas id="forceRetentionRate"></canvas>
			</div>
			<div class="row">
				<div class="col-md-6">
					<canvas id="forceSignUps"></canvas>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="container">
	<h1><center>Reviews</center></h1>
		<div class="row">
			<div class="col-md-6">
				<canvas id="reviewsRetentionRate"></canvas>
			</div>
			<div class="row">
				<div class="col-md-6">
					<canvas id="reviewsSignUps"></canvas>
				</div>
			</div>
		</div>
	</div>
</div>



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

// $chartData = array(

// 		'chartName' => '', 
// 		'xAxisName' => '',
// 		'yAxisName' => '',
// 		'data' => array()
// 	);

// $experimentData = array(

// 		'name' => '',
// 		'charts' => array(),
// 	);

// $chartData = array(

// 		'SlashPage' => , 
// 	);







$visitors = array();
$visitorsPerVar = array();
$retention = array();
$signups = array();

// function addValue(&$array, $key, $value, $modFunction) {

// 	if(isset($array[$key])) {

// 		$modFunction(&$array[$key], $value)
// 	} else {


// 	}
// }

// var_dump($ChartDataArray);

// array_push($ChartDataArray, new Experiment("testing"));
// 	$chart = $ChartDataArray[0]->pushChart('TheChart', 'xaxis', 'yaxis');

// 	$chart->pushDataPair('bob', 5);
// 	$chart->pushDataPair('joe', 7);
// 	$chart->pushDataPair('jill', 584);

// array_push($ChartDataArray, new Experiment("SplashPage"));
// 	$chart = $ChartDataArray[1]->pushChart('splashpage', 'x', 'y');

// array_push($ChartDataArray, new Experiment("visitors"));
// 	$chart = $ChartDataArray[2]->pushChart('VisitorCounter', 'time', 'visitors');





echo "<p>Database dump:</p>";
echo "<table>"; // start a table tag in the HTML
echo "<tr><th colspan='3'>The user interactions log</th></tr>";
echo "<tr><th>SessionID</th><th>Activity</th><th>Session Start Time</th></tr>";

while($row = mysqli_fetch_array($result)){   //Creates a loop to loop through results
	echo "<tr><td>" . $row['SessionID'] . "</td><td>";  //$row['index'] the index here is a field name

	$query = "SELECT COUNT(*) FROM appointments WHERE SessionID='" . $row['SessionID'] . "';";
	$appointmentCount = mysqli_fetch_array(mysqli_query($conn, $query))['COUNT(*)'];

	echo "\n";
	var_dump(intval($appointmentCount));

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

		    if(isset($visitors[$date])) {

		    	$visitors[$date] += 1;
		    } else {

		    	$visitors[$date] = 1;
		    }

		    if(isset($visitorsPerVar[$page->variant])) {

		    	$visitorsPerVar[$page->variant] += 1;
		    } else {

		    	$visitorsPerVar[$page->variant] = 1;
		    }

		    // echo "\nPage Varient loaded: $page->variant\n";

		    if(isset($retention[$page->variant])) {

		    	$retention[$page->variant] += $page->elapsedTimeSec;
		    	$retention[$page->variant] /= 2;
		    } else {

		    	$retention[$page->variant] = $page->elapsedTimeSec;
		    }
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


	if(isset($signups[$page->variant])) {

    	$signups[$page->variant] += $appointmentCount;
    } else {

    	$signups[$page->variant] = $appointmentCount;
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

echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>';

// var_dump($ChartDataArray);

// assign vars for charts


//
//			experiments
//				experiemnt name
//				contained charts
//					chart name
//					axis names
//					data
//					

class chartContainer {

	public $name = '';
	public $xAxisName = '';
	public $yAxisName = '';
	public $data = array();

	public function __construct($name, $xAxis, $yAxis) {

		$this->name = $name;
		$this->xAxisName = $xAxis;
		$this->yAxisName = $yAxis;
	}

	public function pushData($value) {

		array_push($this->data, $value);
	}

	public function pushDataPair($key, $value) {

		$this->data[$key] = $value;
	}

	public function setData($data) {

		$this->data = $data;
	}
}

class Experiment {

	public $name = '';
	public $charts = array();

	public function __construct($name) {

		$this->name = $name;
	}

	public function pushChart($name, $xAxisLable, $yAxisLable) {

		$chart = new chartContainer($name, $xAxisLable, $yAxisLable);

		// array_push($this->charts, $chart);
		$this->charts[$name] = $chart;

		return $chart;
		// return $this->charts[count($this->charts) - 1];
	}

}


$ChartDataArray = array();

$ChartDataArray["VisitorCounter"] = new Experiment("VisitorCounter");
$ChartDataArray["VisitorCounter"]->pushChart('VisitorCounter', 'xaxis', 'yAxis');
$ChartDataArray["VisitorCounter"]->pushChart('visitorsTotal', 'xaxis', 'yAxis');

$ChartDataArray["splash"] = new Experiment("splashRetentionRate");
$ChartDataArray["splash"]->pushChart('splashRetentionRate', 'x', 'y');
$ChartDataArray["splash"]->pushChart('splashSignUps', 'x', 'y');

$ChartDataArray["moreInfo"] = new Experiment("moreInfo");
$ChartDataArray["moreInfo"]->pushChart('infoRetentionRate', 'x', 'y');
$ChartDataArray["moreInfo"]->pushChart('infoSignUps', 'x', 'y');

$ChartDataArray["reinforcements"] = new Experiment("reinforcements");
$ChartDataArray["reinforcements"]->pushChart('forceRetentionRate', 'x', 'y');
$ChartDataArray["reinforcements"]->pushChart('forceSignUps', 'x', 'y');

$ChartDataArray["reviews"] = new Experiment("reviews");
$ChartDataArray["reviews"]->pushChart('reviewsRetentionRate', 'x', 'y');
$ChartDataArray["reviews"]->pushChart('reviewsSignUps', 'x', 'y');



var_dump($visitors);
var_dump($retention);
var_dump($signups);

krsort($visitorsPerVar);
krsort($retention);
krsort($signups);

foreach ($visitors as $key => $value) {
	$ChartDataArray['VisitorCounter']->charts['VisitorCounter']->data[$key] = $value;
}

foreach ($visitorsPerVar as $key => $value) {
	$ChartDataArray['VisitorCounter']->charts['visitorsTotal']->data[$key] = $value;
}

// splash page
foreach ($retention as $key => $value) {
	if($key == 1 || $key == 8) {

		$ChartDataArray['splash']->charts['splashRetentionRate']->data[$key] = $value;
	}
}
foreach ($signups as $key => $value) {
	if($key == 1 || $key == 8) {

		$ChartDataArray['splash']->charts['splashSignUps']->data[$key] = $value;
	}
}

// more information
foreach ($retention as $key => $value) {
	if($key == 1 || ($key >= 9 && $key <= 12)) {

		$ChartDataArray['moreInfo']->charts['infoRetentionRate']->data[$key] = $value;
	}
}
foreach ($signups as $key => $value) {
	if($key == 1 || ($key >= 9 && $key <= 12)) {

		$ChartDataArray['moreInfo']->charts['infoSignUps']->data[$key] = $value;
	}
}

// reinforcements
foreach ($retention as $key => $value) {
	if($key == 1 || $key == 13) {

		$ChartDataArray['reinforcements']->charts['forceRetentionRate']->data[$key] = $value;
	}
}
foreach ($signups as $key => $value) {
	if($key == 1 || $key == 13) {

		$ChartDataArray['reinforcements']->charts['forceSignUps']->data[$key] = $value;
	}
}

// reviews
foreach ($retention as $key => $value) {
	if($key >= 1 && $key <= 7) {

		$ChartDataArray['reviews']->charts['reviewsRetentionRate']->data[$key] = $value;
	}
}
foreach ($signups as $key => $value) {
	if($key >= 1 && $key <= 7) {

		$ChartDataArray['reviews']->charts['reviewsSignUps']->data[$key] = $value;
	}
}

//     // if(isset($ChartDataArray[$date])) {
//     if(isset($ChartDataArray['VisitorCounter']->charts['VisitorCounter']->data[$date])) {

//     	// $ChartDataArray[$date] += 1;
//     	$ChartDataArray['VisitorCounter']->charts['VisitorCounter']->data[$date] += 1;
//     } else {

//     	// $ChartDataArray[$date] = 1;
//     	$ChartDataArray['VisitorCounter']->charts['VisitorCounter']->data[$date] = 1;
//     }

//     //var_dump($chartData);

//     // if($page->variant == 5 || $page->variant == 6) {

// 	    if(isset($ChartDataArray['splash']->charts['splashRetentionRate']->data[$page->variant])) {

// 	    	// $ChartDataArray[$date] += 1;
// 	    	$ChartDataArray['splash']->charts['splashRetentionRate']->data[$page->variant] += $page->elapsedTimeSec;
// 	    	$ChartDataArray['splash']->charts['splashRetentionRate']->data[$page->variant] /= 2;
// 	    } else {

// 	    	// $ChartDataArray[$date] = 1;
// 	    	$ChartDataArray['splash']->charts['splashRetentionRate']->data[$page->variant] = $page->elapsedTimeSec;
// 	    }
// 	// }


// if(isset($ChartDataArray['splash']->charts['splashSignUps']->data[$page->variant])) {

// 	    	// $ChartDataArray[$date] += 1;
// 	    	$ChartDataArray['splash']->charts['splashSignUps']->data[$page->variant] += intval($appointmentCount);
// 	    } else {

// 	    	// $ChartDataArray[$date] = 1;
// 	    	$ChartDataArray['splash']->charts['splashSignUps']->data[$page->variant] = intval($appointmentCount);
// 	    }

echo "\n<script>";

foreach ($ChartDataArray as $key => $experiment) {
	// var_dump($key);
	// var_dump($experiment);
	foreach ($experiment->charts as $chart) {

		echo "\nvar ctx = document.getElementById('$chart->name');";
		echo "\nvar myChart = new Chart(ctx, {";
		echo "\n	type: 'bar',";
		echo "\n	data: {";
		echo "\n		labels: [" . "'" . implode("', '", array_reverse(array_keys($chart->data))) . "'" . "],";
		echo "\n		datasets: [{";

		echo "\n			label: '" . "name..." . "',";
		echo "\n			data: [" .  implode(', ', array_reverse($chart->data)) . "],
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

			";
		echo "\n		}]";
		echo "\n	},";
		echo "\n
					options: {

						title: {
							display: true,
							text: '$chart->name'
						},

						legend: {
							display: false
						}
					}


			";
		echo "\n});";

	}
}

echo "</script>";


	// echo "var ctx = document.getElementById($experiemnt->name);";
	// echo "var myChart = new Chart(ctx, {";
	// echo "	type: 'line',";
	// echo "	data: {";
	// echo "		labels: [echo "'" . implode("', '", array_reverse(array_keys($chartData))) . "'";],";
	// echo "		datasets: [{";

	// echo "			label: 'Page Loads',";
	// echo "			data: [ echo implode(', ', array_reverse($chartData));],";
	// echo "			backgroundColor: [ 'rgba(255, 102, 1, 1)'],";
	// echo "			borderColor: ['rgba(255, 102, 1, 0.2)']";
	// echo "		}]";
	// echo "	}";
	// echo "});";


?>





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
			maintainAspectRatio: true,
			responsive: true,
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
