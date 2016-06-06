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



fputcsv($analysis, array('Hashtag', 'Count'));

$hashtagCounts = array_count_values($totalHashtags);
arsort($hashtagCounts);

foreach ( $hashtagCounts as $key => $value ) {
	$data = array($key, $value);
	fputcsv($analysis, $data);
}


 
// Close the file
fclose($file);
fclose($analysis);





$rootPath = realpath($dirname);

// Initialize archive object
$zip = new ZipArchive();
$zip->open('output/'.$identifier.'.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

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



header("Content-type: application/zip"); 
header("Content-Disposition: attachment; filename=".$identifier.".zip"); 
header("Pragma: no-cache"); 
header("Expires: 0"); 
readfile("output/".$identifier.".zip");

// header("Location: /?success=".$identifier);
?>