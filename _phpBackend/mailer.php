<?php

function mailSlaves($reservations, $contacts, $newsletters) {

	// Make inserts unordered lists
	$reservationsstring = '';
	foreach ($reservations as &$string) {

		$reservationsstring .= "<li>" . $string . "</li>";
	}
	unset($string);

	$contactsstring = '';
	foreach ($contacts as &$string) {

		$contactsstring .= "<li>" . $string . "</li>";
		// echo $string . '<br>';
	}
	unset($string);

	$newslettersstring = '';
	foreach ($newsletters as &$string) {

		$newslettersstring .= "<li>" . $string . "</li>";
	}
	unset($string);


	$mailbody = file_get_contents("_assets/mailerAssets/newsletter.html");
	//print 'file contents recieved: ' . $mailbody;

	//print 'hello world?? are you therre???<br>';

	$replacements = array(
		'{$newreservations}'	=> count($reservations),
		'{$reservations}'	=>	$reservationsstring,

		'{$newcontacts}'	=>	count($contacts),
		'{$contacts}'	=>	$contactsstring,

		'{$newnews}'	=>	count($newsletters),
		'{$newsletters}'	=>	$newslettersstring
		);

	$mailbody = strtr($mailbody, $replacements);
	// print "Mailer with modifications: ". $mailbody;

	$header = "";
	$header .= "From: mailbot@you2industries.com\r\n";
	$header .= "Repy-To: hudson.kendall@you2industries.com\r\n";
	$header .= "Content-Type: text/html; charset=utf-8\r\n";

	$recievers = '';
	$recievers .= 'you2industries@mail.com';
	$recievers .= ', hudson.kendall@you2industries.com';
	$recievers .= ', jack.fernald@you2industries.com';

	if(mail($recievers, "New form entries", $mailbody, $header)) {

		// print 'Mail function was successful.<br>';
	} else {

		// print 'mail send was a FAILURE.<br>';
	}

}


?>