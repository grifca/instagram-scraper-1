<?php

$max_size = $_POST['MAX_FILE_SIZE'];
$group_name = $_POST['match-group-name'];

$message = 'default';
$valid_file = true;


if($_FILES['files'])
{
  //if no errors...
  if(!$_FILES['files']['error'])
  {
    //now is the time to modify the future file name and validate the file
    $new_file_name = strtolower($group_name); //rename file
    if($_FILES['files']['size'] > ($max_size)) //can't be larger than 1 MB
    {
      $valid_file = false;
      $state = 'fail';
    }
    
    //if the file has passed the test
    if($valid_file)
    {
      $fileContents = file_get_contents($_FILES['files']['tmp_name']);
      file_put_contents('matches/'.$new_file_name.'.csv', $fileContents);
      $state = 'success';
    }
  }
  //if there is an error...
  else
  {
    $state = 'fail';
  }

  header("Location: matches.php?s=".$state);
}
?>