<?php
require_once $_SERVER['DOCUMENT_ROOT']."/config.php";
include SITE_ROOT.'/db_connect.php';

if(isset($_GET['id_attraction'], $_GET['date']) && !is_nan($_GET['id_attraction'])){
  $booking_exists_stmt = $con->prepare("SELECT id FROM reservé WHERE id_attraction = ? AND date = ?");
  $booking_exists_stmt->bind_param("is", $_GET['id_attraction'], $_GET['date']);
  if($booking_exists_stmt->execute()){
      $booking_exists_stmt->store_result();
      if($booking_exists_stmt->num_rows > 0){
          echo "1";
      }else{
          echo "0";
      }
  }
}
?>
