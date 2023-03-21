<?php
  $DATABASE_HOST="localhost";
  $DATABASE_USER="root";
  $DATABASE_NAME="attraction";
  $DATABASE_PASSWORD="";
  $con=mysqli_connect($DATABASE_HOST,$DATABASE_USER,$DATABASE_PASSWORD,$DATABASE_NAME);

  if(mysqli_connect_errno()){
  	die("Failed to connect to MYSQL :".mysqli_connect_error());
  }

  mysqli_set_charset($con, "utf8");
?>
