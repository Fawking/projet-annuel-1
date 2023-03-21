<?php
    if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==TRUE && $_SESSION['type_compte']=="admin"){
            ?>
function deleteRow(id){
       <?php if($page_name=="dashboard"){
  echo "var rowType = 'attraction';";
  $pronom = "cette";
 }else if($page_name=="reservations"){
  echo "var rowType = 'reservation';";
  $pronom = "cette";
 }else if($page_name=="utilisateurs"){
  echo "var rowType = 'utilisateur';";
  $pronom = "cet";
 }else if($page_name=="membres"){
  echo "var rowType = 'membre';";
  $pronom = "ce";
 }
 ?>

  	var $confirm = confirm("Êtes-vous sûr de vouloir supprimer <?php echo $pronom; ?> "+rowType+"? cette action est irréversible !");
 
  	if($confirm){
      $.ajax({
  method: "POST",
  url: "delete_row_ajax.php",
  data: {
 <?php if($page_name=="dashboard"){
 	echo "id_attraction";
 }else if($page_name=="reservations"){
 	echo "id_reservation";
 }else if($page_name=="utilisateurs"){
 	echo "id_utilisateur";
 }else if($page_name=="membres"){
 	echo "id_membre";
 }
 ?> : id,
   }
}).done(function(response) {

	if(response == "success"){

		var message = rowType+" supprimé avec succés!";

		$(".dashboard").prepend("<div class='success'><p>"+message+" la page va se recharger aprés 5s...</p></div>").hide().fadeIn("slow"); 

            $("html, body").animate({scrollTop : 0},0);
            //,0
            

            setTimeout(function(){
            	location.reload();
            },8000)
        

	}else if(response == "error"){


		var message = "Une erreur s'est produite lors de la suppression de  <?php echo $pronom; ?> "+rowType+" veuillez réessayer!";

		$(".dashboard").prepend("<div class='error'><p>"+message+"</p></div>");
		$(window).animate(
                { scrollTop: "0" }, 3000);
	}

}).fail(function(xhr, status, error) {

         $(".dashboard").prepend("<div class='error'><p>Une erreur s'est produite lors de la suppression de  <?php echo $pronom; ?> "+rowType+" veuillez réessayer! </p></div>"); 
    })
  }

  }
  <?php } ?>