<?php
//	Starts session
if(!isset($_SESSION)) session_start();

//	Error reporting:
	// Testing Only
ini_set('display_errors', 1);
ini_set('display_startup_error', 1);
error_reporting(E_ALL);

//	Include the splitTesting.php file
require_once "_phpBackend/splitTest.php";
//	Include statistic record keeping file
require_once "_phpBackend/siteStats.php";


//	Load a split tested page
$pageLoaded = loadAlternate(basename(__FILE__, ".php"));

pageLoadStat($pageLoaded);


?>

<script src="_js/ifvisible.js"></script>
<script src="_js/timeme.js"></script>

<script>

TimeMe.setIdleDurationInSeconds(60);
TimeMe.setCurrentPageName("index");
TimeMe.initialize();


window.onbeforeunload = function (event) {
	xmlhttp=new XMLHttpRequest();
	xmlhttp.open("POST","_phpBackend/recordElapsed.php",false);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	var timeSpentOnPage = TimeMe.getTimeOnCurrentPageInSeconds();
	xmlhttp.send("time="+timeSpentOnPage);

};



</script>