<?php
require_once $_SERVER['DOCUMENT_ROOT']."/config.php";
ob_start();
session_start();
include SITE_ROOT.'/db_connect.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<title>Mes réservations</title>
<?php include 'global_head_tags.php'; ?>
<link href='fullcalendar/lib/main.css' rel='stylesheet' />
<script src='fullcalendar/lib/main.js'></script>
</head>
<body>
<?php
$page_name = "mes_reservations";
include 'nav.php';
?>
<div class="wrapper">
<div class="mes_reservations">
<?php
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==TRUE){
        if($_SESSION['type_compte']=="client"){
?>


    <div class='calendrier' id="calendrier">   
    </div>
<div class='dashboard'>
    <button class='expand_button' onclick="expand()"><i class='fa fa-expand'></i>&nbsp;Etendre les colonnes restantes</button>
    
    <table>
        <tr>
            <th>Reservation</th>
            <th>Attraction</th>

            <th>Date</th>
            <th>Nb tickets</th>
            <th>Nb enfants</th>
            <th>Nb adultes</th>
            <th>Montant</th>
            <th>Créé le</th>

            <th class='extension'>Pays</th>
            <th class='extension'>Ville</th>
            <th class='extension'>Etat</th>
            <th class='extension'>Adresse 1</th>
            <th class='extension'>Adresse 2</th>
            <th class='extension'>Code ZIP</th>
        </tr>
        <?php
        if (!isset ($_GET['page']) || $_GET['page']<1) {  
            $current_page = 1;  
        } else {
            $current_page = $_GET['page'];  
        } 
        $results_per_page = DASHBOARD_NUMBER_OF_RESULTS_PER_PAGE;  

        $page_first_result = ($current_page-1) * $results_per_page; 
        $stmt1 = $con->prepare("SELECT id_attraction, date FROM reservé WHERE id_utilisateur = ?");
        $stmt1->bind_param("i", $_SESSION['id_utilisateur']);
        $stmt1->execute();
        $tous_les_reservations = $stmt1->get_result();
        $nombre_de_tous_les_reservations = $tous_les_reservations->num_rows;
        $number_of_pages = ceil ($nombre_de_tous_les_reservations  / $results_per_page);

         $stmt = $con->prepare("SELECT id, id_attraction, nb_tickets, nb_enfants, nb_adultes, date, montant_payé, créé, pays, ville, état, adresse1, adresse2, code_zip FROM reservé WHERE id_utilisateur = ? ORDER BY créé DESC LIMIT ".$page_first_result.",".$results_per_page);
         $stmt->bind_param("i", $_SESSION['id_utilisateur']);
         if($stmt->execute()){
            $resultat_reservations = $stmt->get_result();
        if($resultat_reservations->num_rows > 0){

            $i = $page_first_result;
            while($reservation = $resultat_reservations->fetch_assoc()){
                $i++;
                echo "<tr>";
                $id_reservation = $reservation['id'];
                $id_attraction_reservation = $reservation['id_attraction'];
                $resultat_nom_attraction = $con->query("SELECT nom FROM attractions WHERE id = ".$id_attraction_reservation);
                if($resultat_nom_attraction->num_rows > 0){
                    $nom_attraction = $resultat_nom_attraction->fetch_assoc()['nom'];
                }else{
                    $nom_attraction = "";
                }
                $nb_tickets_reservation = $reservation['nb_tickets'];
                $nb_enfants_reservation = $reservation['nb_enfants'];
                $nb_adultes_reservation = $reservation['nb_adultes'];

                $date_reservation = $reservation['date'];
                $montant_reservation = $reservation['montant_payé'];

                $cree = $reservation['créé'];

                $pays_reservation = $reservation['pays'];
                $ville_reservation = $reservation['ville'];
                $etat_reservation = $reservation['état'];
                $adresse1_reservation = $reservation['adresse1'];
                $adresse2_reservation = $reservation['adresse2'];
                $code_zip_reservation = $reservation['code_zip'];

                $date_datetime = new DateTime($date_reservation);
                $date_en_format = $date_datetime->format("d/m/Y");
                $cree_datettime = new DateTime($cree);
                $cree_en_format = $cree_datettime->format("d/m/Y");
                
                echo "<td>#".$i."</td>
                <td>".$nom_attraction."</td>
                <td>".$date_en_format."</td>
                <td>".$nb_tickets_reservation."</td>
                <td>".$nb_enfants_reservation."</td>
                <td>".$nb_adultes_reservation."</td>
                <td>€ ".$montant_reservation."</td>
                <td>".$cree_en_format."</td>";
                echo "<td class='extension'>".$pays_reservation."</td>
                <td class='extension'>".$ville_reservation."</td>
                <td class='extension'>".$etat_reservation."</td>
                <td class='extension'>".$adresse1_reservation."</td>
                <td class='extension'>".$adresse2_reservation."</td>
                <td class='extension'>".$code_zip_reservation."</td>";
                echo "</tr>";
            }
       
        }
        
        ?>

    </table>

    <div class="pagination">
        <?php
        if($current_page >1){ 
            echo "<a href='?page=1'>&laquo;</a>";
        }
        for($page = 1; $page<= $number_of_pages; $page++) {  
            if($page==$current_page){
                echo '<a class="active" href="?page='.$page.'">'.$page.' </a>';
            }else{
                echo '<a href="?page='.$page.'">'.$page.' </a>';
            }
        }
        if($current_page < $number_of_pages){ 
            echo '<a href="?page='.$number_of_pages.'">&raquo;</a>';
        } 

    
        ?>
    </div>
    </div>

    <?php 
}
?>

<?php
        }else{
            echo "<div class='error'><p>Erreur : Cette page est réservée aux clients uniquement</p></div>";
        }
    }else{
    header("Location: /login");
    }
?>
</div>
<?php include 'footer.php'; ?>
</div>
<script>
const getCellValue = (tr, idx) => tr.children[idx].innerText || tr.children[idx].textContent;

const comparer = (idx, asc) => (a, b) => ((v1, v2) => 
v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2)
)(getCellValue(asc ? a : b, idx), getCellValue(asc ? b : a, idx));

document.querySelectorAll('th').forEach(th => th.addEventListener('click', (() => {
const table = th.closest('table');
Array.from(table.querySelectorAll('tr:nth-child(n+2)'))
.sort(comparer(Array.from(th.parentNode.children).indexOf(th), this.asc = !this.asc))
.forEach(tr => table.appendChild(tr) );
})));
</script>
<script>
      var $displayed = 0;
    const expand = () => {
        $(".wrapper1").scroll(function(){
        $(".wrapper2")
            .scrollLeft($(".wrapper1").scrollLeft());
    });
  
        if($(".extension").css("display")=="none"){
    
            $(".extension").fadeIn("slow")
            $(".dashboard").prepend("<div class='snackbar'>vous pouvez faire défiler vers la droite et la gauche en utilisant les flèches du clavier</div>")
            if(!$displayed){
                $(".snackbar").fadeIn(1000)
                $(".snackbar").fadeOut(6000)
                $displayed = 1;
            }   
        }else{
            $(".extension").fadeOut()
        }
        
        
    }
</script>
<script>

document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendrier');
  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    contentHeight:"auto",
    events:[
        <?php
        if(isset($tous_les_reservations) && $tous_les_reservations->num_rows > 0){
            while($row = $tous_les_reservations->fetch_assoc()){
            $resultat_nom_attraction_2 = $con->query("SELECT nom FROM attractions WHERE id=".$row['id_attraction']);
            if($resultat_nom_attraction_2->num_rows > 0){
                $nom_attraction_2 = $resultat_nom_attraction_2->fetch_assoc()['nom'];
            }
            $date_reservation_2 = $row['date'];
            echo "
            {
                title: '".$nom_attraction_2."',
                start: '".$date_reservation_2."',
            },";
        }
        }
        ?>
    ],
  });
  calendar.render();
});

</script>
</body>
</html>