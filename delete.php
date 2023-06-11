<?php 
require "data.php";

//we will need to check to see that this page was hit using post and more importantly that the movie_id is there.

if(isset($_POST['number'])) {
  $index = array_key_first(array_filter($invoices, function($invoice){
    return $invoice['number'] == $_POST['number'];
  }));

  unset($_SESSION['invoices'][$index]);
}
header("Location: index.php");