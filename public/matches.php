<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css"> 

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
  <a class="navbar-brand" href="history.php">History</a>
</nav>

<?php if($_GET['s'] == 'success') : ?>
    <div class="alert alert-success" role="alert" id="alert-compress">
        <i class="fa fa-check" aria-hidden="true"></i>
        <strong>Match Group Upload Successful</strong> 
        You can now use this in the extraction tool
    </div>
<?php elseif($_GET['s'] == 'fail') : ?>
    <div class="alert alert-danger" role="alert" id="alert-compress">
        <i class="fa fa-times" aria-hidden="true"></i>
        <strong>Match Group Upload Failed</strong> 
        You may want to try that again
    </div>
<?php endif; ?>


<?php
$max_file_size = 30000000; // size in bytes 
?>



<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Add New Match List</h4>
            </div>
            <form method="post" action="match-upload.php" enctype="multipart/form-data">
                <div class="modal-body">
                    <fieldset class="form-group">
                        <label for="match-group-name">Match Group Name:</label>
                        <input type="text" id="match-group-name" name="match-group-name" class="form-control">
                    </fieldset>
                    <label for="files">Match List CSV:</label>
                    <fieldset class="form-group">
                        <label class="file">    
                            <input type="hidden" name="MAX_FILE_SIZE" id="MAX_FILE_SIZE" value="<?php echo $max_file_size ?>"> 
                            <input type="file" id="files" name="files">
                            <span class="file-custom"></span>
                        </label>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Upload New Match List</button>
                </div>
            </form>
        </div>
    </div>
</div>

<h1>View Match Lists</h1>

<?php 

$outputs = glob('matches/*'); 

if($outputs) {

    echo '<div class="list-group">';

    foreach($outputs as $dir => $identifier) {
        $identifier = ltrim($identifier, 'output/');
        echo '<a href="success.php?i='.$identifier.'" class="list-group-item">'.$identifier.'</a>';
    }

    echo '</div>';
}
?>

<hr>

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
  Add New Match List
</button>


<script   src="https://code.jquery.com/jquery-2.2.4.min.js"   integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="   crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js"></script>

</body>
</html>