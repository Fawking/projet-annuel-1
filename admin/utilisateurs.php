<?php
require_once $_SERVER['DOCUMENT_ROOT']."/config.php";
ob_start();
session_start();
include SITE_ROOT.'/db_connect.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<title>Tableau de bord : utilisateurs</title>
<?php include SITE_ROOT.'/global_head_tags.php'; ?>
</head>
<body>
<?php
$page_name = "utilisateurs";
include SITE_ROOT.'/admin/admin-nav.php';
?>
<div class="wrapper">
    <div class="dashboard_page_container">
    <?php
    if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==TRUE){
        if($_SESSION['type_compte']=="admin"){
            ?>
        <?php include SITE_ROOT.'/sidebar.php'; ?>
        <div class="dashboard">

            <table>
                <tr>
                    <th>Utilisateur</th>
                    <th>Nom complet</th>
                    <th>Email</th>
                    <th>Pays</th>
                    <th>Ville</th>
                    <th>Réservations</th>
                    <th>Créé le</th>
                    <th>Actions</th>
                </tr>
                <?php
                if (!isset ($_GET['page']) || $_GET['page']<1) {  
                    $current_page = 1;  
                } else {
                    $current_page = $_GET['page'];  
                } 
                $results_per_page = DASHBOARD_NUMBER_OF_RESULTS_PER_PAGE;  

                $page_first_result = ($current_page-1) * $results_per_page; 
				$tous_les_utilisateurs = $con->query("SELECT id FROM utilisateurs");
				$nombre_de_tous_les_utilisateurs = $tous_les_utilisateurs->num_rows;

                $number_of_pages = ceil ($nombre_de_tous_les_utilisateurs  / $results_per_page);

                $resultat_utilisateurs = $con->query("SELECT id, nom_complet, email, pays, ville, créé FROM utilisateurs ORDER BY créé DESC LIMIT ".$page_first_result.",".$results_per_page);
                if($resultat_utilisateurs->num_rows > 0){

                    


                    while($utilisateur = $resultat_utilisateurs->fetch_assoc()){
                        echo "<tr>";
                        $id_utilisateur = $utilisateur['id'];
                        $nom_complet_utilisateur = $utilisateur['nom_complet'];
                        $email_utilisateur = $utilisateur['email'];
                        $pays_utilisateur = $utilisateur['pays'];
                        $ville_utilisateur = $utilisateur['ville'];
                        $cree = $utilisateur['créé'];
                        $cree_datettime = new DateTime($cree);
                        $cree_en_format = $cree_datettime->format("d/m/Y");
                        $resultat_nb_reservations = $con->query("SELECT id FROM reservé WHERE id_utilisateur=".$id_utilisateur);
                        $nb_reservations = $resultat_nb_reservations->num_rows;
                        echo "<td>#".$id_utilisateur."</td>
                        <td>".$nom_complet_utilisateur."</td>
                        <td>".$email_utilisateur."</td>
                        <td>".$pays_utilisateur."</td>
                        <td>".$ville_utilisateur."</td>
                        <td>".$nb_reservations."</td>
                        <td>".$cree_en_format."</td>";
                        echo "<td>
                        <button class='delete' onclick='deleteRow(".$id_utilisateur.")'><i class='far fa-trash-alt'></i></button>
                        </td>";
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
        }else{
            echo "<div class='error'><p>Erreur : Cette page est réservée aux administrateurs uniquement</p></div>";
        }
    }else{
        header("Location: /login");
    }
			?>
    </div>
</div>
<script>
    $(document).ready(function(){
  $(".add_button").click(function(){
    $(".add_form").slideToggle("slow");
  });
});
</script>

<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
<script>
	<?php include 'delete_row_js.php'; ?>
</script>
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
<script src='/index.js'></script>
</body>
</html>