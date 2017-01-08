<!--		Testing ONLY		-->
<?php
//	Starts session
if(!isset($_SESSION)) session_start();

//	Error reporting:
ini_set('display_errors', 1);
ini_set('display_startup_error', 1);
error_reporting(E_ALL);

$_SESSION = array();
session_destroy();

echo "session ended.";

?>