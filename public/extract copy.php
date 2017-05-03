<?php

set_time_limit(3000);

if(isset($_GET['i'])) {

	$identifier = $_GET['i'];
	$totalHashtags = array();

	$file = fopen('datasets/'. $identifier . '/scrape_'. $identifier . '.csv','r');

	// var_dump($file);

	while (($row = fgetcsv($file, 0, ",")) !== FALSE) {
	    //Dump out the row for the sake of clarity.
		$hashtags = array();
		preg_match_all("/#(\\w+)/", $row[2], $hashtags);
		// var_dump($hashtags);					

		foreach($hashtags[0] as $hashtag) {
			$totalHashtags[] = strtolower($hashtag);
		}
	}
	fclose($file);


	$dirname = 'hashtags/'.$identifier;

	mkdir($dirname, 0777, true);

	// var_dump($totalHashtags);


	$hashtagFile = fopen('hashtags/'. $identifier . '/hashtags_'. $identifier . '.csv','w');
	fputcsv($hashtagFile, $totalHashtags);
	fclose($hashtagFile);

}
?>