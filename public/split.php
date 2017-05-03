<?php

$bigFile = fopen("scrape_test.csv", "r");
$j = 0;

while(! feof($bigFile)) {
    $smallFile = fopen("small$j.csv", "w");
    $j++;

    for ($i = 0; $i < 1000 && ! feof($bigFile); $i++) {
        fwrite($smallFile, fgets($bigFile));

    }
    fclose($smallFile);

}
fclose($bigFile);
unlink("paf.csv");

?>