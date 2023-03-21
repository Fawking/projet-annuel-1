<?php
require_once $_SERVER['DOCUMENT_ROOT']."/config.php";

include SITE_ROOT.'/db_connect.php';


if (isset($_POST['id_attraction'])){

$id_attraction = $_POST['id_attraction'];

if(!is_nan($id_attraction)){

  $con->query("DELETE FROM attractions WHERE id=".$id_attraction);
  if($con->error){
  	echo "error";
  }else{
  	echo "success";
  }
}
}

if(isset($_POST['id_reservation'])){
	$id_reservation = $_POST['id_reservation'];
	if(!is_nan($id_reservation)){
		$con->query("DELETE FROM reservé WHERE id=".$id_reservation);
		if($con->error){
			echo "error";
		}else{
			echo "success";
		}
	}
}

if (isset($_POST['id_utilisateur'])){

	$id_utilisateur = $_POST['id_utilisateur'];

	if(!is_nan($id_utilisateur)){
		$con->query("DELETE FROM utilisateurs WHERE id=".$id_utilisateur );
		if($con->error){
			echo "error";
		}else{
			echo "success";
		}
	}
}

if (isset($_POST['id_membre'])){

$id_membre = $_POST['id_membre'];

	if(!is_nan($id_membre)){
		$con->query("DELETE FROM admins WHERE id=".$id_membre);
		if($con->error){
			echo "error";
		}else{
			echo "success";
		}
	}
}

?>