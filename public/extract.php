<?php

set_time_limit(300);

if(isset($_GET['i'])) {

	$identifier = $_GET['i'];
	$totalHashtags = array();

	$file = fopen('datasets/'. $identifier . '/scrape_'. $identifier . '.csv','r');

	while (($row = fgetcsv($file, 0, ",")) !== FALSE) {
	    //Dump out the row for the sake of clarity.
	    var_dump($row[1]);
		$hashtags = array();
		preg_match_all("/#(\\w+)/", $row[1], $hashtags);					

		foreach($hashtags[0] as $hashtag) {
			$totalHashtags[] = strtolower($hashtag);
		}
	}
	fclose($file);


	$dirname = 'hashtags/'.$identifier;

	mkdir($dirname, 0777, true);


	$hashtagFile = fopen('hashtags/'. $identifier . '/hashtags_'. $identifier . '.csv','w');
	fputcsv($hashtagFile, $totalHashtags);
	fclose($hashtagFile);

}
?>