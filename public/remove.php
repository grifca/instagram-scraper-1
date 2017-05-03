<?php
// array to hold all "seen" lines
$lines = array();

// open the csv file
if (($handle = fopen("date_tester.csv", "r")) !== false) {
    // read each line into an array
    while (($data = fgetcsv($handle, 8192, ",")) !== false) {
        // build a "line" from the parsed data
        $line = join(",", $data);

        // if the line has been seen, skip it
        if (isset($lines[$line])) continue;

        // save the line
        $lines[$line] = true;
    }
    fclose($handle);
}

// build the new content-data
$contents = '';
foreach ($lines as $line => $bool) $contents .= $line . "\r\n";

// save it to a new file
file_put_contents("date_tester_clean.csv", $contents);
?>