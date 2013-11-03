<?php

include 'dbAndHashIDs.php';
setlocale(LC_ALL, 'en_US.UTF8'); // required for proper cleaning of url titles

// FIRST WE DETECT IF THERE IS A LOC SET AND BASED ON THAT REDIRECT
// VERY BAD BEHAVIOR IF THE DB HAS A RFC VALID BUT NON RESOLVABLE URL STORED LIKE htp://blogger.com
$id = _INPUT('hashid');
if (isset($id)) {
	// PARSE IT CORRECTLY for crazy cases of hack attempts.
	// FIRST GET A RECORD NUMBER
	$recordNum = hashIDToRecordNumber($id);
	// CHECK IF IT EXISTS IN DB
	if (DBRecordExists($recordNum)) {
		// IF YES, THE REDIR TO THE LOCATION
		redirect(getDBRecord($recordNum));
	} else {
		// OTHERWISE, REDIRECT TO AN ERROR PAGE OR DEFAULT.
		redirect('http://www.example.com/');
	}	
}

function redirect($url, $statusCode = 303) {
   	// CRITICAL READING: http://stackoverflow.com/questions/768431/how-to-make-a-redirect-in-php/
	// SECURITY: http://thedailywtf.com/Articles/WellIntentioned-Destruction.aspx
	header('Location: ' . $url, true, $statusCode);
	die();
}

function getShortURL($hashid){
	$record = hashIDToRecordNumber($hashid);
	$destination = getFromDB($record);
	return $destination;
}

function _INPUT($name) {
// THIS FUNCTION GRABS GET OR POST VARS AND STRIPS HTML TAGS FOR NAIVE SECURITY
	if ($_SERVER['REQUEST_METHOD'] == 'GET')
		return strip_tags($_GET[$name]);
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
		return strip_tags($_POST[$name]);
}


?>
