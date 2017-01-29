<?php
if(!isset($_SESSION)) session_start();

//	Error reporting:
ini_set('display_errors', 1);
ini_set('display_startup_error', 1);
error_reporting(E_ALL);

require("siteStats.php");

//		Need to check if it is undefigned or not:

//if(!is_null($_GET['actionID'])) {
	pageTimeStat($_POST['time']);
//} else {
	// testing only
	//echo "Invalid URL, no action id send.";
//}




?>