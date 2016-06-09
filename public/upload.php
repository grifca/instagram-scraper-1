<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css"> 
<link rel="stylesheet" href="font-awesome.min.css"> 

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

    @keyframes spinner {
      0% {
        transform: rotate(0deg);
      }
      100% {
        transform: rotate(360deg);
      }
    }

    .spinner {
      animation: spinner 0.75s infinite linear;
    }

    .fa-check, .fa-times, .fa-spinner {
      display: none;
    }

    .alert-success .fa-check {
      display: inline-block;
    }

    .alert-danger .fa-times {
      display: inline-block;
    }

    .alert-info .fa-spinner {
      display: inline-block;
    }
</style>
</head>
<body>

<nav class="navbar navbar-fixed-top navbar-dark">
  <a class="navbar-brand" href="/">Extractor</a>
  <a class="navbar-brand" href="history.php">History</a>
</nav>

<?php

// var_dump($_POST);
// var_dump($_FILES);
$max_size = $_POST['MAX_FILE_SIZE'];
$message = 'default';
$valid_file = true;


if($_FILES['files'])
{
  //if no errors...
  if(!$_FILES['files']['error'])
  {
    //now is the time to modify the future file name and validate the file
    $new_file_name = strtolower($_FILES['files']['tmp_name']); //rename file
    if($_FILES['files']['size'] > ($max_size)) //can't be larger than 1 MB
    {
      $valid_file = false;
      $message = 'Oops!  Your file\'s size is to large.';
    }
    
    //if the file has passed the test
    if($valid_file)
    {
      $fileContents = file_get_contents($_FILES['files']['tmp_name']);
      file_put_contents('uploads/'.$_FILES['files']['name'], $fileContents);
      $message = 'Congratulations!  Your file was accepted.';
      $state = 'success';
    }
  }
  //if there is an error...
  else
  {
    $message = 'Ooops!  Your upload triggered the following error:  '.$_FILES['files']['error'];
  }
}

?>





<?php if($state == 'success') : ?>

  <div class="alert alert-success" role="alert">
      <i class="fa fa-check" aria-hidden="true"></i>
      <strong>File Upload Successful</strong> <?php echo $message; ?>
  </div>

  <div class="alert" role="alert" id="alert-extraction">
      <i class="fa fa-times" aria-hidden="true"></i>
      <i class="fa fa-spinner spinner" aria-hidden="true"></i>
      <i class="fa fa-check" aria-hidden="true"></i>
      <strong>Instagram Extraction</strong> 
      This will extract all Instagram posts into a CSV file 
  </div>


  <div class="alert" role="alert" id="alert-hashtag-extraction">
      <i class="fa fa-times" aria-hidden="true"></i>
      <i class="fa fa-spinner spinner" aria-hidden="true"></i>
      <i class="fa fa-check" aria-hidden="true"></i>
      <strong>Hashtag Extraction</strong> 
      This will extract all hashtags from the posts
  </div>


  <div class="alert" role="alert" id="alert-hashtag-collate">
      <i class="fa fa-times" aria-hidden="true"></i>
      <i class="fa fa-spinner spinner" aria-hidden="true"></i>
      <i class="fa fa-check" aria-hidden="true"></i>
      <strong>Hashtag Collation</strong> 
      This will collate all unique hashtags along with a count
  </div>


  <div class="alert" role="alert" id="alert-hashtag-categorise">
      <i class="fa fa-times" aria-hidden="true"></i>
      <i class="fa fa-spinner spinner" aria-hidden="true"></i>
      <i class="fa fa-check" aria-hidden="true"></i>
      <strong>Hashtag Categorisation</strong> 
      This will match all hashtags against your chosen list
  </div>


  <div class="alert" role="alert" id="alert-compress">
      <i class="fa fa-times" aria-hidden="true"></i>
      <i class="fa fa-spinner spinner" aria-hidden="true"></i>
      <i class="fa fa-check" aria-hidden="true"></i>
      <strong>Hashtag Categorisation</strong> 
      This will match all hashtags against your chosen list
  </div>
  <hr>
  <button type="button" class="btn btn-lg btn-primary" id="download-report" disabled="">Download Report</button>

<?php else : ?>

  <div class="alert alert-danger" role="alert">
    <i class="fa fa-times" aria-hidden="true"></i>
    <strong>File Upload Failed</strong> <?php echo $message; ?>
  </div>

<?php endif; ?>


<script   src="https://code.jquery.com/jquery-2.2.4.min.js"   integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="   crossorigin="anonymous"></script>

<script type="text/javascript">
$(document).ready(function(){

  <?php if($state == 'success') : ?>
    breakdownFile();
  <?php endif; ?>

  $('#download-report').click(function() {
    downloadReport();
  });
});


var identifier;


function breakdownFile() {
  $('#alert-extraction').addClass('alert-info');

  $.ajax({
    url: "script.php?file=<?php echo $_FILES['files']['name']; ?>", //Relative or absolute path to response.php file
    success: function(data) {
      console.log(data);

      identifier = data;

      if(identifier != 'error') {
        $('#alert-extraction').removeClass('alert-info').addClass('alert-success');
        extractHashtags();
      }
    },
    error: function(data) {
      $('#alert-extraction').removeClass('alert-info').addClass('alert-danger');
    }
  });
}


function extractHashtags() {
  $('#alert-hashtag-extraction').addClass('alert-info');
  $.ajax({
    url: "extract.php?i="+identifier, //Relative or absolute path to response.php file
    success: function(data) {
      console.log(data);

      $('#alert-hashtag-extraction').removeClass('alert-info').addClass('alert-success');
      collateHashtags();
    },
    error: function(data) {
      $('#alert-extraction').removeClass('alert-info').addClass('alert-danger');
    }
  });
}


function collateHashtags() {
  $('#alert-hashtag-collate').addClass('alert-info');
  $.ajax({
    url: "collate.php?i="+identifier, //Relative or absolute path to response.php file
    success: function(data) {
      console.log(data);

      $('#alert-hashtag-collate').removeClass('alert-info').addClass('alert-success');
      categoriseHashtags();
    },
    error: function(data) {
      $('#alert-hashtag-collate').removeClass('alert-info').addClass('alert-danger');
    }
  });
}


function categoriseHashtags() {
  $('#alert-hashtag-categorise').addClass('alert-info');
  $.ajax({
    url: "categorise.php?i="+identifier, //Relative or absolute path to response.php file
    success: function(data) {
      console.log(data);

      $('#alert-hashtag-categorise').removeClass('alert-info').addClass('alert-success');
      compressFiles();
    },
    error: function(data) {
      $('#alert-hashtag-categorise').removeClass('alert-info').addClass('alert-danger');
    }
  });
}


function compressFiles() {
  $('#alert-compress').addClass('alert-info');
  $.ajax({
    url: "compress.php?i="+identifier, //Relative or absolute path to response.php file
    success: function(data) {
      console.log(data);

      $('#alert-compress').removeClass('alert-info').addClass('alert-success');
      enableButton();
    },
    error: function(data) {
      $('#alert-compress').removeClass('alert-info').addClass('alert-danger');
    }
  });
}

function enableButton() {
  $('#download-report').attr('disabled', false);
}

function downloadReport() {
  if($('#download-report').prop('disabled') == false) {
    alert('test');
    location.href=identifier+'.zip';
  }
}
</script>


</body>
</html>