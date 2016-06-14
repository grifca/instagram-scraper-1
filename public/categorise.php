<?php

set_time_limit(3000);

if(isset($_GET['i'])) {
	$identifier = $_GET['i'];

	$matchArray = array();
	$matchCategories = array();

	$matches = fopen('matches.csv','r');

	$rowCount = 0;

	while (($matchrow = fgetcsv($matches, 0, ",")) !== FALSE) {
	    // var_dump($matchrow);

	    foreach ($matchrow as $key => $match) {
	    	if($rowCount == 0) {
	    		$matchArray[$match] = array();
	    		$matchCategories[$match] = 0;
	    	} else {
	    		$cityNames = array_keys($matchArray);
					$city = $cityNames[$key];
	    		$matchArray[$city][] = $match;
	    	}
	    	
    		
	    }

	    $rowCount++;
	}

	// var_dump($matchArray);


	fclose($matches);



	$file = fopen('output/'. $identifier . '/hashtag-counts_'. $identifier . '.csv','r');

	while (($row = fgetcsv($file, 0, ",")) !== FALSE) {
	    // var_dump($row);
	    // $hashtags = array_count_values($row);
	    // var_dump($hashtagCounts);
    $hashtag = $row[0];
    $hashtagCount = $row[1];
			// var_dump($hashtag);

		foreach ( $matchArray as $groupKey => $matchGroup ) {
			// var_dump($matchGroup);
			foreach ( $matchGroup as $matchKey => $match ) {
				if(strtolower($match) == $hashtag) {
					var_dump($match);
					var_dump($hashtag);
					var_dump($groupKey);
					$existingTally = $matchCategories[$groupKey];
					var_dump($existingTally);
					var_dump($hashtagCount);
					$matchCategories[$groupKey] = $existingTally + $hashtagCount;
					var_dump($matchCategories[$groupKey]);
				}
				else {
				}
			}
		}
	}


	fclose($file);


	mkdir('output/'. $identifier . '/reports/', 0777, true);
	$reportFile = fopen('output/'. $identifier . '/reports/report_'. $identifier . '.csv','w');
	fputcsv($reportFile, array('Match Group', 'Count'));
	// arsort($matchCategories);


	arsort($matchCategories);
	var_dump($matchCategories);

	foreach ( $matchCategories as $key => $cat ) {
		fputcsv($reportFile, array($key, $cat));
	}



	fclose($reportFile);





}
?>