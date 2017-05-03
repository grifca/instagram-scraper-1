<?php
//returns a big old hunk of JSON from a non-private IG account page.
function scrape_insta() {
	$insta_source = file_get_contents('https://www.instagram.com/explore/tags/engaged/');
	$shards = explode('window._sharedData = ', $insta_source);
	$insta_json = explode(';</script>', $shards[1]); 
	$insta_array = json_decode($insta_json[0], TRUE);
	return $insta_source;
}
//Do the deed
$results_array = scrape_insta();
var_dump($results_array);

?>