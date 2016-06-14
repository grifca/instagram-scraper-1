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
</nav>

<?php 

$outputs = glob('output/*'); 

if($outputs) {

    echo '<div class="list-group">';

    foreach($outputs as $dir => $identifier) {
        $identifier = ltrim($identifier, 'output/');
        echo '<a href="view.php?i='.$identifier.'" class="list-group-item">'.$identifier.'</a>';
    }

    echo '</div>';
}
?>


</body>
</html>