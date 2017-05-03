<html>
<head></head>
<body>

<?php

set_time_limit(3000000000000);

if(isset($_GET['i'])) {

	$identifier = $_GET['i'];
	$totalHashtags = array();


$datasets = glob('datasets/'.$identifier.'/*'); 

if($datasets) {
	echo '<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>';
    echo '<script>';
    echo "var javascript_array = [";

    foreach($datasets as $dir => $group) {
        // echo $group;


		$file = fopen( $group ,'r');

		// var_dump($file);

		while (($row = fgetcsv($file, 0, ",")) !== FALSE) {
		    //Dump out the row for the sake of clarity.
		    $js_array = json_encode($row);
			echo $js_array . ",\n";
		}

		fclose($file);


    }




// $dirname = 'locations/'.$identifier;

// 	mkdir($dirname, 0777, true);

// 	var_dump($totalHashtags);


// 	$hashtagFile = fopen('hashtags/'. $identifier . '/hashtags_'. $identifier . '.csv','w');
// 	fputcsv($hashtagFile, $totalHashtags);
// 	fclose($hashtagFile);
    echo '];';

	echo '</script>';
}

}
?>

<script>
	for (var p in javascript_array) { locatePage(javascript_array[p][1]); }
</script>

</body>
</html>