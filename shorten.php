<?php

include 'cleanTitle.php';
include 'dbAndHashIDs.php';
// include 'validURL.php'; // UNFORTUNATELY THIS FUNCTION ISNT WORKING WELL, BUT WE REALLY NEED SOME
// THING MORE LIKE THIS.

$url = _INPUT('url');
if (isset($url)) {
	$json  = createShortURL($url);
	echo $json;
} else {
	$return['hasError'] = TRUE;
	$return['errorDesc'] =  "Oops. Did you provide a url?";
	$return['shortlink'] = '';
	echo json_encode($return);
}

function _INPUT($name) {
// THIS FUNCTION GRABS GET OR POST VARS AND STRIPS HTML TAGS FOR NAIVE SECURITY
	if ($_SERVER['REQUEST_METHOD'] == 'GET')
		return strip_tags($_GET[$name]);
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
		return strip_tags($_POST[$name]);
}

function isValidUrl($url) {
	// ITS A TEMP SOLUTION. IDEALLY WE SHOULD CHECK THE URL TO SEE IF IT EXISTS AND DISSALOW FTP ETC.
	return !(filter_var($url, FILTER_VALIDATE_URL) == false);
}

function createShortURL($url) {
	// VALIDATE URL
	if (!isValidUrl($url)) {
		$return['hasError'] = TRUE;
		$return['errorDesc'] =  "Oops. Double check that your link is valid!";
		$return['shortlink'] = '';
		return json_encode($return);
	}

	// First check how many elements in "DB"
	$latestRecordNum = getLatestRecordNum();
	// Increment by one
	$newRecordNum = $latestRecordNum + 1;
	// Generate unique id in ASCII char space
	$id = recordNumberToHashID($newRecordNum);
	// map id to url (save in db)
	newRecord($newRecordNum,$url);
	// CONSTRUCT A LINK
	$link = theDomainName() . (string) $id;

	// FORM ARRAY TO RETUNS
	$return['hasError'] = FALSE;
	$return['errorDesc'] =  '';
	$return['shortlink'] = $link;

	return json_encode($return);
}

function getLatestRecordNum() {
	$num = file_get_contents(latestRecordNumFilename());
	return $num;
}

function writeLatestRecordNum($newRecordNum) {
	file_put_contents(latestRecordNumFilename(), $newRecordNum);
}

function newRecord($newRecordNum, $url) {
	writeLatestRecordNum($newRecordNum);
	saveInDatabase($newRecordNum, $url);
}

// CONSTANTS
function theDomainName() { return "http://localhost/projects/bitly/go.php?hashid=";}
function latestRecordNumFilename(){ return 'latest_record.txt';}


?>
