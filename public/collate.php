<?php

set_time_limit(300);

if(isset($_GET['i'])) {

	$identifier = $_GET['i'];


	$file = fopen('output/'. $identifier . '/hashtags_'. $identifier . '.csv','r');

	while (($row = fgetcsv($file, 0, ",")) !== FALSE) {
	    // var_dump($row);
	    $hashtagCounts = array_count_values($row);
	    // var_dump($hashtagCounts);
	}

	fclose($file);


	$hashtagFile = fopen('output/'. $identifier . '/hashtag-counts_'. $identifier . '.csv','w');


	arsort($hashtagCounts);
	
	foreach ( $hashtagCounts as $key => $value ) {
		$hastagText = strtolower(ltrim($key, '#'));

		$data = array($key, $value);
		fputcsv($hashtagFile, $data);
	}


	fputcsv($hashtagFile, $totalHashtags);
	fclose($hashtagFile);

}
?>