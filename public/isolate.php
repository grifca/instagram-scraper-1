<?php

$file  = fopen('clean.csv', 'r');

$query = '#'.$_GET['q'];

// You can use an array to store your search words, makes things more flexible.
// Supports any number of search words.
$words = array($query);    
// Make the search words safe to use in regex (escapes special characters)
$words = array_map('preg_quote', $words);
// The argument becomes '/wii|guitar/i', which means 'wii or guitar, case-insensitive'
$regex = '/'.implode('|', $words).'/i';

while (($line = fgetcsv($file)) !== FALSE) {  
    list($name, $age, $hobbies) = $line;

    $image = trim($age);

    if(preg_match($regex, $hobbies)) {
        echo "<a href='https://www.instagram.com/p/".$image."' target='_blank'><img src='https://instagram.com/p/"."$image"."/media/?size=t'></a>\n";
    }
}

?>