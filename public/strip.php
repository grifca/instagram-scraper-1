<?php
$input = 'order.csv';
$output = 'order_stripped.csv';

if (false !== ($ih = fopen($input, 'r'))) {
    $oh = fopen($output, 'w');

    while (false !== ($data = fgetcsv($ih))) {
        // this is where you build your new row
        $outputData = array($data[0]);
        fputcsv($oh, $outputData);
    }

    fclose($ih);
    fclose($oh);
}

?>