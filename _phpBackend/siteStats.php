<?php


function pageTimeStat($time) {


	updateDatabaseEnty($time, function($jsonData, $time) {


		//	Get the current time for the timestamp value
		$timestamp = new DateTime();
		$timestamp = date_timezone_set($timestamp, new DateTimeZone('UTC'));

		$actionObj = json_encode(

			array(

				"elapsedTimeSec" => $time
			)
		);

		//	Decode the two fragments
		$jsonIn = json_decode($jsonData);
		$jsonAdd =  json_decode($actionObj);

		//	join the fragments			BUG:	If the last page opened is not the current page being browsed(multitabed browsing) then actions could be appended to the wrong page.
		//array_push($jsonIn[count($jsonIn) - 1], $jsonAdd);
		$jsonIn[count($jsonIn) - 1]->elapsedTimeSec = "$time";

		//var_dump($jsonIn);

		//	Encode the new whole
		$jsonOut = json_encode($jsonIn);

		//	return the final json string
		return $jsonOut;
	});
}

//	Record a page visit, 
function pageLoadStat($pageName) {

	

	updateDatabaseEnty($pageName, function($jsonData, $pageName) {

		//	Get action parameter from URL
		$referer = 'none';

		//	Determine if the user was directed to this page externaly or from with it using the url/index.html?reference=bla parameter
		if (isset($_GET['reference'])) {

			//	If there is a URL param, use it.
		    $referer =  $_GET['reference'];
		}else{

			//	Otherwise, assume external linkage.
		    $referer =  'external';
		}

		//	Extract page information for database entry
		$pageData = explode("_", $pageName);
		$pageName = $pageData[0];
		$pageNameSplit = explode(".", $pageData[1]);
		$pageVariant = $pageNameSplit[0];

		//	Get the current time for the timestamp value
		$timestamp = new DateTime();
		// Use UTC time instead of server local time
		$timestamp = date_timezone_set($timestamp, new DateTimeZone('UTC'));

		//	construct the json string to be appended to the json array recieved through $jsonData
		$pageObj = json_encode(array(
			"page" => "$pageName",
			"variant" => (int)$pageVariant,
			"reference" => "$referer",
			"timeStamp" => $timestamp->format('Y-m-d H:i:s'),
			"elapsedTimeSec" => "0",
			"actions" => array(

				)
		));

		//	Testing only
		// echo '<br>pageObj:<br>';
		// var_dump($pageObj);
		// echo '<br>';

		//	Decode the two fragments
		$jsonIn = json_decode($jsonData);
		$jsonAdd =  json_decode($pageObj);

		//	join the fragments
		array_push($jsonIn, $jsonAdd);

		//	Encode the new whole
		$jsonOut = json_encode($jsonIn);

		//	Testing Only
		// echo 'jsonIN:<br>';
		// var_dump($jsonIn);
		// echo '<br>';
		// echo 'JsonAdd:<br>';
		// var_dump($jsonAdd);
		// echo '<br>';

		//	return the final json string
		return $jsonOut;
	});


}

function actionStat($actionID) {

	updateDatabaseEnty($actionID, function($jsonData, $actionID) {

		//	Get the current time for the timestamp value
		$timestamp = new DateTime();
		$timestamp = date_timezone_set($timestamp, new DateTimeZone('UTC'));

		$actionObj = json_encode(

			array(

				"elementID" => "$actionID",
				"timeStamp" => $timestamp->format('Y-m-d H:i:s')
			)
		);

		//	Decode the two fragments
		$jsonIn = json_decode($jsonData);
		$jsonAdd =  json_decode($actionObj);

		//	Testing Only
		// echo '<br>jsonIN:<br>';
		// var_dump($jsonIn);
		// echo '<br>';

		//	join the fragments			BUG:	If the last page opened is not the current page being browsed(multitabed browsing) then actions could be appended to the wrong page.
		array_push($jsonIn[count($jsonIn) - 1]->actions, $jsonAdd);

		//	Testing
		// echo '<br>testing: <br>';
		// var_dump($jsonIn[count($jsonIn) - 1]->actions);
		// echo '<br>';

		//	Encode the new whole
		$jsonOut = json_encode($jsonIn);

		//	Testing Only
		// echo '<br>jsonIN:<br>';
		// var_dump($jsonIn);
		// echo '<br>';
		// echo 'JsonAdd:<br>';
		// var_dump($jsonAdd);
		// echo '<br>';

		//	return the final json string
		return $jsonOut;

	});
}

//	Insert into the database
function updateDatabaseEnty($dataRelay, $modifyJson) {

	//	Get php server info
	require("sqldbinfo/info.php");

	//	get the session ID
	$session = session_id();

	//	Testing ONly
	// echo "sessionID: " . $session . '<br>';

	//	Get the json file for the current user if it exisits
	$query = "SELECT visitorStats.path from visitorStats where visitorStats.SessionID = '$session';";

	//$query = "SELECT * from visitorStats;";	TODO: Use $result to make json data for insert command below
	$result = mysqli_query($conn, $query);

	//	Testing ONly
	// var_dump($result);
	// echo '<br>';

	//	Check if a row exists for this session
	if(mysqli_num_rows($result) == 0) {
	//	Testing only
	// if(true) {
		//	If there isn't one, add a row and json data

		//	Testing ONly
		// echo 'no rows in search<br>';

		//		Generate a json data
		// Data to encode:

		// 	users
		// 		Page
		// 			page name
		// 			Page variant loaded
		// 			reference action
		// 			timestamp
		//			Time spent on page
		// 			actions taken on page
		// 				button clicks
		//					element id
		//					timestamp
		//			


		// 	json string layout:

		// 	Array
		// 		Object
		// 			key: text
		// 			key: int
		// 			key: text
		// 			key: int
		//			key: int
		// 			key: Array
		// 				Object
		// 					text
		// 					text
		// 				Object
		//					text
		//					text
		//				...
		//
		// 		Object
		// 			key: text
		// 			key: int
		// 			key: text
		// 			key: int
		// 			key: Array
		// 				Object
		// 					text
		// 					text
		// 				Object
		//					text
		//					text
		//				...
		// 		...
			
		$jsonData = json_encode(
			array(
				
			)
		);

		//	Testing
		// var_dump($jsonData);

		//	Add data
		$jsonData = $modifyJson($jsonData, $dataRelay);

		//	Insert a row.	TODO: add 'path' column to the insert query with json data
		 $query = "INSERT INTO visitorStats (SessionID, path, SessionStartTime) VALUES ('$session', '$jsonData', now());";

		 //	Testing
		 // var_dump($query);

		 if(mysqli_query($conn, $query)) {

		 	//	Testing ONly
		 	// echo "db insert success<br>";

		 } else {

		 	//	Testing ONly
		 	// echo "db insert failed<br>";
		 }

	} else {
		//	If there is one, append to the 'actions' json array in the 'path' column

		//	Testing ONly
		// echo "found rows. <br>";

		$sqlresult = mysqli_fetch_array($result, MYSQLI_NUM);
		$jsonData = $sqlresult[0];

		//		Testing only
		// var_dump($jsonData);

		//		Allow the json data to be modified
		$jsonData = $modifyJson($jsonData, $dataRelay);

		$query = "UPDATE visitorStats SET path='$jsonData' WHERE SessionID='$session';";

		//	testing
		// var_dump($query);

		 if(mysqli_query($conn, $query)) {

		 	//	Testing ONly
		 	// echo "db insert success<br>";

		 } else {

		 	//	Testing ONly
		 	// echo "db insert failed<br>";
		 }




		 
	}
	//	If ther is something, get the path and update the approgaopsdijgf avalues.

	//	Update the row's path value wiht new json.



	//	Should be the same as this:

		// //	Get php server info
	// require("sqldbinfo/info.php");

	// //	get the session ID
	// $session = session_id();

	// //	Testing Only
	// echo $referer . ".  Hi.<br>";

	// //	check if there is a database row for this user
	// $query = "SELECT 1 FROM visitorStats WHERE SessionID = '$session';";
	// $response = mysqli_query($conn, $query);

	// //	Testing only
	// var_dump($response);

	// if(mysqli_num_rows($response) == 0) {
	// 	//	If there is no matching row data, make one

	// 	//	Testing
		// echo "no database entry";
	// } else {
	// 	//	If ther is a match, get the json and append a page object

	// 	//	Testing
		// echo "found database entry";

	// }

	// //	If so, add this page data as a new object int the json array

	// //	If not, generate a row for this user based on the session id and generate the json data structure.
}

/*	
	Data to encode:

	users
		Page
			page name
			Page variant loaded
			reference action
			time spent on page
			actions taken on page
				button clicks


	json string layout:

	Array
		Object
			key: text
			key: int
			key: text
			key: int
			key: Array
				text
				text
				text
				...
		Object
			key: text
			key: int
			key: text
			key: int
			key: Array
				text
				text
				text
				...
		...


	example formated in json string:

	'[
		{
			"page": "index", 
			"variant": 1, 
			"reference": "external", 
			"elapsedTimeSec": 52, 
			"actions": 
			[
				"coverImageClick"
				"reservationButtonClick"
			]
		}, 

		{
			"page": "reservations", 
			"variant": 3, 
			"reference": "reservationButton", 
			"elapsedTimeSec": 263, 
			"actions": 
			[
				"submitReservationClick"
				"contactsHeaderMenuClick"
			]
		}
	]'


*/

?>
