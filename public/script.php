<?php


$dataFile = 'data.html';
$identifier = date("m-d-Y_His");

$dirname = 'output/'.$identifier;

mkdir($dirname, 0755, true);

$form = $_POST['htmlinput'];

$html = '<!DOCTYPE html><html><head></head><body>'.$form.'</body></html>';

file_put_contents($dataFile, $html);

$doc = new DOMDocument();
@$doc->loadHTML($html);

$tags = $doc->getElementsByTagName('a');
$totalHashtags = array();

$file = fopen('output/'. $identifier . '/scrape_'. $identifier . '.csv', 'w');
$analysis = fopen('output/'. $identifier . '/analysis_'. $identifier . '.csv', 'w');
 
fputcsv($file, array('ID', 'Caption'));

foreach ($tags as $tag) {

	$linkHref = $tag->getAttribute('href');
	$linkIDarray = explode('/', $linkHref);
	$linkID = $linkIDarray[2];

	foreach($tag->childNodes as $nodename) {

		foreach($nodename->childNodes as $link) {
			foreach($link->childNodes as $image) {
				$imageCaption = $image->getAttribute('alt');
				$imageSrc = $image->getAttribute('src');
				if($image->tagName == 'img') { 
					$data = array($linkID, $imageCaption);
					$hashtags = array();
					preg_match_all("/#(\\w+)/", $imageCaption, $hashtags);
					
					foreach($hashtags[0] as $hashtag) {
						$data[] = $hashtag;
						$totalHashtags[] = $hashtag;
					}

					fputcsv($file, $data);
				}
			}
		}
	}
}



fputcsv($analysis, array('Hashtag', 'Count', 'Country'));

$hashtagCounts = array_count_values($totalHashtags);
arsort($hashtagCounts);

$countries = array("afghanistan", "albania", "algeria", "american samoa", "andorra", "angola", "anguilla", "antarctica", "antigua and barbuda", "argentina", "armenia", "aruba", "australia", "austria", "azerbaijan", "bahamas", "bahrain", "bangladesh", "barbados", "belarus", "belgium", "belize", "benin", "bermuda", "bhutan", "bolivia", "bosnia and herzegowina", "botswana", "bouvet island", "brazil", "british indian ocean territory", "brunei darussalam", "bulgaria", "burkina faso", "burundi", "cambodia", "cameroon", "canada", "cape verde", "cayman islands", "central african republic", "chad", "chile", "china", "christmas island", "cocos (keeling) islands", "colombia", "comoros", "congo", "congo, the democratic republic of the", "cook islands", "costa rica", "cote d'ivoire", "croatia (hrvatska)", "cuba", "cyprus", "czech republic", "denmark", "djibouti", "dominica", "dominican republic", "east timor", "ecuador", "egypt", "el salvador", "equatorial guinea", "eritrea", "estonia", "ethiopia", "falkland islands (malvinas)", "faroe islands", "fiji", "finland", "france", "france metropolitan", "french guiana", "french polynesia", "french southern territories", "gabon", "gambia", "georgia", "germany", "ghana", "gibraltar", "greece", "greenland", "grenada", "guadeloupe", "guam", "guatemala", "guinea", "guinea-bissau", "guyana", "haiti", "heard and mc donald islands", "holy see (vatican city state)", "honduras", "hong kong", "hungary", "iceland", "india", "indonesia", "iran (islamic republic of)", "iraq", "ireland", "israel", "italy", "jamaica", "japan", "jordan", "kazakhstan", "kenya", "kiribati", "korea, democratic people's republic of", "korea, republic of", "kuwait", "kyrgyzstan", "lao, people's democratic republic", "latvia", "lebanon", "lesotho", "liberia", "libyan arab jamahiriya", "liechtenstein", "lithuania", "luxembourg", "macau", "macedonia, the former yugoslav republic of", "madagascar", "malawi", "malaysia", "maldives", "mali", "malta", "marshall islands", "martinique", "mauritania", "mauritius", "mayotte", "mexico", "micronesia, federated states of", "moldova, republic of", "monaco", "mongolia", "montserrat", "morocco", "mozambique", "myanmar", "namibia", "nauru", "nepal", "netherlands", "netherlands antilles", "new caledonia", "new zealand", "nicaragua", "niger", "nigeria", "niue", "norfolk island", "northern mariana islands", "norway", "oman", "pakistan", "palau", "panama", "papua new guinea", "paraguay", "peru", "philippines", "pitcairn", "poland", "portugal", "puerto rico", "qatar", "reunion", "romania", "russian federation", "rwanda", "saint kitts and nevis", "saint lucia", "saint vincent and the grenadines", "samoa", "san marino", "sao tome and principe", "saudi arabia", "senegal", "seychelles", "sierra leone", "singapore", "slovakia (slovak republic)", "slovenia", "solomon islands", "somalia", "south africa", "south georgia and the south sandwich islands", "spain", "sri lanka", "st. helena", "st. pierre and miquelon", "sudan", "suriname", "svalbard and jan mayen islands", "swaziland", "sweden", "switzerland", "syrian arab republic", "taiwan, province of china", "tajikistan", "tanzania, united republic of", "thailand", "togo", "tokelau", "tonga", "trinidad and tobago", "tunisia", "turkey", "turkmenistan", "turks and caicos islands", "tuvalu", "uganda", "ukraine", "united arab emirates", "united kingdom", "united states", "united states minor outlying islands", "uruguay", "uzbekistan", "vanuatu", "venezuela", "vietnam", "virgin islands (british)", "virgin islands (u.s.)", "wallis and futuna islands", "western sahara", "yemen", "yugoslavia", "zambia", "zimbabwe");

foreach ( $hashtagCounts as $key => $value ) {
	$hastagText = strtolower(ltrim($key, '#'));

	if(in_array($hastagText, $countries)) {
		$countryStatus = 'true';
	} else {
		$countryStatus = 'false';
	}
	$data = array($key, $value, $countryStatus);
	fputcsv($analysis, $data);
}


 
// Close the file
fclose($file);
fclose($analysis);





$rootPath = realpath($dirname);

// Initialize archive object
$zip = new ZipArchive();
$zip->open($identifier.'.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

// Create recursive directory iterator
/** @var SplFileInfo[] $files */
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($rootPath),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($files as $name => $file)
{
    // Skip directories (they would be added automatically)
    if (!$file->isDir())
    {
        // Get real and relative path for current file
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($rootPath) + 1);

        // Add current file to archive
        $zip->addFile($filePath, $relativePath);
    }
}

// Zip archive will be created only after closing object
$zip->close();



// header("Content-type: application/zip"); 
// header("Content-Disposition: attachment; filename=".$identifier.".zip"); 
// header("Pragma: no-cache"); 
// header("Expires: 0"); 
// readfile("output/".$identifier.".zip");

header("Location: success.php?i=".$identifier);
?>