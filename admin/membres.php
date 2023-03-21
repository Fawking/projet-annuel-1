<?php
require_once $_SERVER['DOCUMENT_ROOT']."/config.php";
ob_start();
session_start();
include SITE_ROOT.'/db_connect.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<title>Tableau de bord : membres</title>
<?php include SITE_ROOT.'/global_head_tags.php'; ?>
</head>
<body>
<?php
$page_name = "membres";
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
                    <th>Membre</th>
                    <th>Email</th>
                    <th>Username</th>
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
				$tous_les_membres = $con->query("SELECT id FROM reservé");
				$nombre_de_tous_les_membres = $tous_les_membres->num_rows;

                $number_of_pages = ceil ($nombre_de_tous_les_membres  / $results_per_page);

                $resultat_membres = $con->query("SELECT id,username,email,créé FROM admins ORDER BY créé DESC LIMIT ".$page_first_result.",".$results_per_page);
                if($resultat_membres->num_rows > 0){

                    
                    while($membre = $resultat_membres->fetch_assoc()){
                        echo "<tr>";

                        $id_membre = $membre['id'];
                        $email_membre = $membre['email'];
                        $username_membre = $membre['username'];
                        $cree = $membre['créé'];

                        $cree_datettime = new DateTime($cree);
                        $cree_en_format = $cree_datettime->format("d/m/Y");
                        
                        echo "<td>#".$id_membre."</td>
                        <td>".$email_membre."</td>
                        <td>".$username_membre."</td>
                        <td>".$cree_en_format."</td>";
                        if($id_membre != $_SESSION['id_admin']){
                            echo "<td>
                            <button class='delete' onclick='deleteRow(".$id_membre.")'><i class='far fa-trash-alt'></i></button>
                            </td>";
                        }else{
                            echo "<td></td>";
                        }
                        
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