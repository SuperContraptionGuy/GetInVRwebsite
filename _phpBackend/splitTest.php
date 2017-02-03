<?php

		// Author:	Hudson Kendall

		// Purpose of this file:

		// 	To provide a single function for all the [page].php files in the root directory to load a randomized file in a set of files for the purpose of Split Testing.





//	Called by [page].php files to include the appropriate [page].html file
function loadAlternate($pageName) {

	$killSess = false;

	//	The number of alternate files for each page
	$alternates = array(
		'index' => 1
		);


	if(array_key_exists("$pageName", $alternates)) {

		//	Initialize the variable and set it depening on the if statment
		$pageIndex = 0;

		if(array_key_exists($pageName, $_SESSION)) {

			//	Retrieve the session key and use it to load the page instead.
			$pageIndex = $_SESSION[$pageName];

			//	Debug:
			// echo "<p>Found session.  Using Session value: $pageIndex</p>";

			$killSess = true;

		} else {

			//	Get the number of alternate pages
			$alts = $alternates[$pageName];

			//	Choose an index of the random pages
			$pageIndex = rand(1, $alts);

			//	Set the _SESSION variable
			$_SESSION[$pageName] = $pageIndex;

			//	Debug:
			// echo "<p>No current session.  Setting session value: $pageIndex</p>";

			
		}

		//	Construct the string equal to the page's name
		//	Format: [page]_[x].html where index 'x' starts at 1
		//	Example: index_3.html
		//$loadPageName = $pageName . '_' . $pageIndex . '.html';
		//	Switched to only .php file names.
		$loadPageName = $pageName . '_' . $pageIndex . '.php';

		//	Include the specified file for display to the user
		require '_html/' . $pageName . '/' . $loadPageName;

		// Insert a corisponding mySQL database entry to record the page loaded.		TODO:

		return $loadPageName;

		


	} else {
		//	Undefigned page in the array.
		echo "<p>no such page found, or a spelling error was encountered.  ('$pageName' does not exist...)</p>";
	}

	//	For Debug:
	// if($killSess) {

	// 	$_SESSION = array();
	// 	session_destroy();
	// }
}


?>