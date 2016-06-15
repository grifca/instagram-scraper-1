<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css"> 
<link rel="stylesheet" href="https://rawgit.com/wenzhixin/bootstrap-table/master/src/bootstrap-table.css">

<style>
    body {
        padding: 80px 40px 40px;
    }

    textarea {
        margin: 0 0 20px;
    }

    .input-group {
        margin: 0 0 20px;
    }

    .navbar {
        background: black;
    }

    .navbar a {
        color: white;
    }
</style>
</head>
<body>

<nav class="navbar navbar-fixed-top navbar-dark">
  <a class="navbar-brand" href="/">Extractor</a>
  <a class="navbar-brand" href="datasets.php">Datasets</a>
  <a class="navbar-brand" href="matches.php">Matches</a>
  <a class="navbar-brand" href="reports.php">Reports</a>
</nav>


<div id="end_id"></div>


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

$returned_content = get_data('https://www.instagram.com/explore/tags/engaged/?max_id=AQCadDuaeX3fjZMPW0o4UDaTSShWBfz2nQCxBO');

$doc = new DOMDocument();
@$doc->loadHTML($returned_content);

$tags = $doc->getElementsByTagName('script');

foreach ($tags as $tag) {
    $scriptContent = $tag->nodeValue; 
    echo '<script>'.$scriptContent.'</script>';
}

?>

<script   src="https://code.jquery.com/jquery-2.2.4.min.js"   integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="   crossorigin="anonymous"></script>
<script>

var endID = 2;
$(document).ready(function(){
    endID = window._sharedData.entry_data.TagPage[0].tag.media.page_info.end_cursor;
    $('#end_id').text(endID);
    fetchPage();
});


    function breakdownFile() {
      $.ajax({
        url: "https://www.instagram.com/explore/tags/engaged/", //Relative or absolute path to response.php file
        success: function(data) {
          console.log(data);
        },
        error: function(data) {
          alert('fail');
        }
      });
    }


    function fetchPage() {
      $.ajax({
        url: "fetch.php?id="+endID, //Relative or absolute path to response.php file
        success: function(data) {
          console.log(data);
        },
        error: function(data) {
          alert('fail');
        }
      });
    }
</script>


</body>
</html>