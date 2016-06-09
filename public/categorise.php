<?php

set_time_limit(3000);

if(isset($_GET['i'])) {
	$identifier = $_GET['i'];

	$matchArray = array();
	$matchCategories = array();

	$matches = fopen('matches.csv','r');

	$rowCount = 0;

	while (($matchrow = fgetcsv($matches, 0, ",")) !== FALSE) {
	    var_dump($matchrow);

	    foreach ($matchrow as $key => $match) {
	    	if($rowCount == 0) {
	    		$matchArray[] = array();
	    		$matchCategories[$match] = 0;
	    	}
	    	
    		$matchArray[$key][] = $match;
	    }

	    $rowCount++;
	}

	var_dump($matchArray);

	fclose($matches);



	$file = fopen('output/'. $identifier . '/hashtag-counts_'. $identifier . '.csv','r');

	while (($row = fgetcsv($file, 0, ",")) !== FALSE) {
	    // var_dump($row);
	    // $hashtags = array_count_values($row);
	    // var_dump($hashtagCounts);
	    $hashtag = $row[0];
	    $hashtagCount = $row[1];
			// var_dump($hashtag);

		foreach ( $matchArray as $key => $matchGroup ) {
			foreach ( $matchGroup as $key => $match ) {
				if($match == $hashtag) {
					$existingTally = $matchCategories[$matchGroup[0]];
					$matchCategories[$matchGroup[0]] = $existingTally + $hashtagCount;
				}
				else {
				}
			}
		}
	}

	fclose($file);


	$reportFile = fopen('output/'. $identifier . '/report_'. $identifier . '.csv','w');
	// arsort($matchCategories);


	arsort($matchCategories);

	foreach ( $matchCategories as $key => $cat ) {
		fputcsv($reportFile, array($key, $cat));
	}



	fclose($reportFile);





}
?>