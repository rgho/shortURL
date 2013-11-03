<?php
// REQUIRES THE LIB
include 'hashids.php';

function saveInDatabase($newRecordNum, $url) {
	file_put_contents('db/' . $newRecordNum . '.loc', $url);
}

function DBRecordExists($recordNum) {
	return file_exists('db/' . $recordNum . '.loc');
}

function getDBRecord($recordNum) {
	return file_get_contents('db/' . $recordNum . '.loc');
}

function recordNumberToHashID($newRecordNum) {
	// CAN ONLY HANDLE 1,000,000,000 records
	// http://www.hashids.org/ 
	$hashids = new Hashids\Hashids(theSalt());
	return $hashids->encrypt($newRecordNum);
}

function hashIDToRecordNumber($id) {
	$hashids = new Hashids\Hashids(theSalt());
	$decryptedArray = $hashids->decrypt($id); // RETURNS AN ARRAY
	return $decryptedArray[0]; // IMPORTANT! WE ONLY NEED THE FIRST ELEMENT
}

function theSalt(){return "THISISMYSALT34892";}

?>