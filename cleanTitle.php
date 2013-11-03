<?php

setlocale(LC_ALL, 'en_US.UTF8'); // required for proper cleaning of url titles

function getCleanedPageTitle($url) {
	// IDEAS FROM http://cubiq.org/the-perfect-php-clean-url-generator
	return toAscii(getPageTitle($url));
}

function toAscii($str, $replace=array(), $delimiter='-') {
	if(!empty($replace)) {
		$str = str_replace((array)$replace, ' ', $str);
	}
	$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
	$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
	$clean = strtolower(trim($clean, '-'));
	$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
	return $clean;
}

function getPageTitle($url){
	if(preg_match('/<title>(.+)<\/title>/', file_get_contents($url),$matches) && isset($matches[1])) {
		$title = $matches[1];
	} else {
		$title = "Untitled Page";
	}
	return $title;
}

?>