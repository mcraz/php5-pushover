<?php

require dirname(__FILE__).'/pushover.class.php';

$pushover = new pushover(array('apiToken' => 'xxxXXXxxx_PUT-YOUR-APPLICATION-KEY-HERE_xxxXXXxxx'));

/*
 *	Sample code
 *	Notification Sending
 *
*/
$user = "xxxXXXxxx_PUT-YOUR-USER/GROUP-KEY-HERE_xxxXXXxxx";
$message = "Checkout this awesome php5 api for Pushover that doen't even require cURL !";

//	Optional Params 
$option['title'] 		= 	"Aweosme Api for Pushover";
$option['url'] 			= 	"https://github.com/mcraz/php5-pushover";
$option['url_title'] 	= 	"Project on Github";
$option['priority'] 	= 	1;
$option['timestamp'] 	= 	time();
$option['sound'] 		= 	"pianobar";

//	Sending notification if user valid validating
if($pushover->validate($user))
	$ApiResopnse = ($pushover->notify($user,$message,$option));
else
	$ApiResopnse = "Invalid User/Group Key";

print_r($ApiResopnse);
