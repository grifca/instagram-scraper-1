<?php

set_time_limit(300);

if(isset($_POST['htmlinput'])) {
  $form = $_POST['htmlinput'];
} elseif(isset($_GET['file'])) {
  $form = file_get_contents('uploads/'.$_GET['file']);
}

if($form) {

	$identifier = date("m-d-Y_His");

	$dirname = 'output/'.$identifier;

	mkdir($dirname, 0755, true);

	$html = '<!DOCTYPE html><html><head></head><body>'.$form.'</body></html>';

	$doc = new DOMDocument();
	@$doc->loadHTML($html);

	$tags = $doc->getElementsByTagName('a');

	$file = fopen('output/'. $identifier . '/scrape_'. $identifier . '.csv', 'w');
	 
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
	          fputcsv($file, $data);
					}
				}
			}
		}
	}

	fclose($file);
	// fclose($hashtagFile);

	echo $identifier;
} else {
	echo 'error';
}
?>