<?php
//	Starts session
if(!isset($_SESSION)) session_start();

//	Error reporting:
ini_set('display_errors', 1);
ini_set('display_startup_error', 1);
error_reporting(E_ALL);

//	Include the splitTesting.php file
require "_phpBackend/splitTest.php";
//	Include statistic record keeping file
require "_phpBackend/siteStats.php";


//	Load a split tested page
$pageLoaded = loadAlternate(basename(__FILE__, ".php"));

pageLoadStat($pageLoaded);


?>