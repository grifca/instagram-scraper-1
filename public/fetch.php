<?php
function get_data($url) {
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

$returned_content = get_data('https://www.instagram.com/explore/tags/engaged/'.$_GET['id']);

$doc = new DOMDocument();
@$doc->loadHTML($returned_content);

$tags = $doc->getElementsByTagName('script');

foreach ($tags as $tag) {
    $scriptContent = $tag->nodeValue; 
    echo '<script>'.$scriptContent.'</script>';
}

?>