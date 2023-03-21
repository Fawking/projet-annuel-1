<?php
require_once $_SERVER['DOCUMENT_ROOT']."/config.php";
ob_start();
session_start();
include SITE_ROOT.'/db_connect.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<title>Tableau de bord</title>
<?php include SITE_ROOT.'/global_head_tags.php'; ?>
</head>
<body>
<?php
$page_name = "dashboard";
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
            <button class='add_button'>Ajouter une attraction</button>
            <?php
            if(isset($_POST['submit'])){
                if(!isset($_POST['nom_attraction'], $_POST['type'], $_POST['age'], $_POST['prix'], $_POST['etat'], $_POST['description']) || empty($_POST['nom_attraction']) || empty($_POST['type']) || empty($_POST['age']) || empty($_POST['prix']) || empty($_POST['etat']) || empty($_POST['description'])){
                    echo "<div class='info'><p>Veuillez remplir tous les champs !</p></div>";
                }else{
                    if (!file_exists($_FILES['photo']['tmp_name']) || !is_uploaded_file($_FILES['photo']['tmp_name'])){
                        echo "<div class='info'><p>Veuillez uploader le fichier de l'image!</p></div>";
                    }else{
                        $target_dir = "/img/attractions/";
                        $target_file = $target_dir.time().basename($_FILES["photo"]["name"]);
                        $file_type=$_FILES['photo']['type'];
                        $file_data=file_get_contents($_FILES['photo']['tmp_name']);
                        $file_error=$_FILES["photo"]["error"];
                        if(!file_exists(SITE_ROOT.$target_dir)){
                            mkdir(SITE_ROOT."/img/attractions",0755,true);
                        }

                        if(!move_uploaded_file($_FILES['photo']['tmp_name'], SITE_ROOT.$target_file)){
                            echo "<div class='error'><p>Une erreur s'est produite lors de l'upload du fichier, veuillez réessayer!</p></div>";
                        }else{
                            $stmt = $con->prepare("INSERT INTO attractions (nom, type, prix, age, état, description, photo, créé_par) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                            $stmt->bind_param("ssdssssi", $_POST['nom_attraction'], $_POST['type'], $_POST['prix'], $_POST['age'], $_POST['etat'], $_POST['description'], $target_file, $_SESSION['id_admin']);
                            if($stmt->execute()){
                                echo "<div class='success'><p>Attraction ajoutée avec succés!</p></div>";
                            }else{
                                echo "<div class='error'><p>Une erreur s'est produite lors de l'ajout de l'attraction, veuillez réessayer ".$stmt->error."!</p></div>";
                            }

                        }
                    }

                }
            }
            ?>
            <div class="add_form">
                <form method="post" enctype="multipart/form-data">
                <h1>Ajouter une attraction</h1>
                <div class='field'>
                        <label for="nom_attraction">Nom de l'attraction</label>
                        <input name="nom_attraction" type="text" id="nom_attraction" required/>
                    </div>
                <div class='inline'>
                    <div class='field'>
                        <label for="age">Age</label>
                        <select id="age" name="age">
                            <option>10 à 12 ans</option>
                            <option>Moins de 10 ans</option>
                        </select>
                    </div>
                    <div class='field'>
                        <label for="type">Type d'attraction</label>
                        <select name="type" id="type" required>
                            <option>Enfants</option>
                            <option>Enfants + Adultes</option>
                        </select>
                    </div>
                </div>

                <div class='inline'>
                    <div class='field'>
                        <label for="prix">Prix</label>
                        <input name="prix" type="number" id="prix" min="0" step="0.01" required>
                        </input>
                    </div>
                    <div class='field'>
                        <label for="etat">Etat</label>
                        <select name="etat" id="etat" required>
                            <option>En marche</option>
                            <option>Suspendue</option>
                        </select>
                    </div>
                </div>
        <div class="field">
        <label for="description">Description</label>
                <textarea name="description" id="description" required></textarea>
            <label for="photo">Photo</label>
            <input type="file" name="photo" id="photo" accept="image/*" required />
            <button type="submit" name="submit">Valider</button>
        </div>
                </form>
            </div>
            <table>
                <tr>
                    <th>Nom de l'attraction</th>
                    <th>Etat</th>
                    <th>Prix du ticket</th>
                    <th>Type</th>
                    <th>Age</th>
                    <th>Créé le</th>
                    <th>Créé par</th>
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
				$tous_les_attractions = $con->query("SELECT id FROM attractions");
				$nombre_de_tous_les_attractions = $tous_les_attractions->num_rows;
                
                $number_of_pages = ceil ($nombre_de_tous_les_attractions  / $results_per_page);

                $resultat_attractions = $con->query("SELECT * FROM attractions ORDER BY créé DESC LIMIT ".$page_first_result.",".$results_per_page);
                if($resultat_attractions->num_rows > 0){

                   


                    while($attraction = $resultat_attractions->fetch_assoc()){
                        echo "<tr>";
                        $id_attraction = $attraction['id'];
                        $nom_attraction = $attraction['nom'];
                        $type_attraction = $attraction['type'];
                        $etat_attraction = $attraction['état'];
                        $age_attraction = $attraction['age'];
                        $prix_attraction = $attraction['prix'];
                        $cree = $attraction['créé'];
                        $cree_datettime = new DateTime($cree);
                        $cree_en_format = $cree_datettime->format("d/m/Y");
                        $cree_par = $attraction['créé_par'];
                        $photo_attraction = $attraction['photo'];
                        $resultat_admin_username = $con->query("SELECT username FROM admins WHERE id=".$cree_par);
                        $admin_username = $resultat_admin_username->fetch_assoc()['username'];
                        echo "<td><a target='_blank' href='/attraction?id=".$id_attraction."'>".$nom_attraction."</a></td>
                        <td>".$etat_attraction."</td>
                        <td>".$prix_attraction." <span>Euro</span></td>
                        <td>".$type_attraction."</td>
                        <td>".$age_attraction."</td>
                        <td>".$cree_en_format."</td>
                        <td>".$admin_username."</td>";
                        echo "<td>
                        <button class='edit' onclick='window.open(\"edit_attraction?id=".$id_attraction."\")'><i class='far fa-edit'></i></button>
                        <button class='delete' onclick='deleteRow(".$id_attraction.")'><i class='far fa-trash-alt'></i></button>
                        </td>";
                        echo "</tr>";
                    }
                }
                
                ?>
                <tr>
                    <td>Attraction alpha</td>
                    <td>En marche</td>
                    <td>50 <span>Euro</span></td>
                    <td>Enfants</td>
                    <td>10 à 12 ans</td>
                    <td>02/07/2021</td>
                    <td>Jhon Doe</td>
                    <td>
                        <button class="edit"><i class="far fa-edit"></i></button>
                        <button class="delete"><i class="far fa-trash-alt"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>Attraction alpha</td>
                    <td>En marche</td>
                    <td>50 <span>Euro</span></td>
                    <td>Enfants</td>
                    <td>10 à 12 ans</td>
                    <td>02/07/2021</td>
                    <td>Jhon Doe</td>
                    <td>
                        <button class="edit"><i class="far fa-edit"></i></button>
                        <button class="delete"><i class="far fa-trash-alt"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>Attraction alpha</td>
                    <td>En marche</td>
                    <td>50 <span>Euro</span></td>
                    <td>Enfants</td>
                    <td>10 à 12 ans</td>
                    <td>02/07/2021</td>
                    <td>Jhon Doe</td>
                    <td>
                        <button class="edit"><i class="far fa-edit"></i></button>
                        <button class="delete"><i class="far fa-trash-alt"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>Attraction alpha</td>
                    <td>En marche</td>
                    <td>50 <span>Euro</span></td>
                    <td>Enfants + Adultes</td>
                    <td>10 à 12 ans</td>
                    <td>02/07/2021</td>
                    <td>Jhon Doe</td>
                    <td>
                        <button class="edit"><i class="far fa-edit"></i></button>
                        <button class="delete"><i class="far fa-trash-alt"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>Attraction alpha</td>
                    <td>En marche</td>
                    <td>50 <span>Euro</span></td>
                    <td>Enfants + Adultes</td>
                    <td>10 à 12 ans</td>
                    <td>02/07/2021</td>
                    <td>Jhon Doe</td>
                    <td>
                        <button class="edit"><i class="far fa-edit"></i></button>
                        <button class="delete"><i class="far fa-trash-alt"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>Attraction alpha</td>
                    <td>En marche</td>
                    <td>50 <span>Euro</span></td>
                    <td>Enfants + Adultes</td>
                    <td>10 à 12 ans</td>
                    <td>02/07/2021</td>
                    <td>Jhon Doe</td>
                    <td>
                        <button class="edit"><i class="far fa-edit"></i></button>
                        <button class="delete"><i class="far fa-trash-alt"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>Attraction alpha</td>
                    <td>En marche</td>
                    <td>50 <span>Euro</span></td>
                    <td>Enfants</td>
                    <td>10 à 12 ans</td>
                    <td>02/07/2021</td>
                    <td>Jhon Doe</td>
                    <td>
                        <button class="edit"><i class="far fa-edit"></i></button>
                        <button class="delete"><i class="far fa-trash-alt"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>Attraction alpha</td>
                    <td>En marche</td>
                    <td>50 <span>Euro</span></td>
                    <td>Enfants</td>
                    <td>10 à 12 ans</td>
                    <td>02/07/2021</td>
                    <td>Jhon Doe</td>
                    <td>
                        <button class="edit"><i class="far fa-edit"></i></button>
                        <button class="delete"><i class="far fa-trash-alt"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>Attraction alpha</td>
                    <td>En marche</td>
                    <td>50 <span>Euro</span></td>
                    <td>Enfants</td>
                    <td>10 à 12 ans</td>
                    <td>02/07/2021</td>
                    <td>Jhon Doe</td>
                    <td>
                        <button class="edit"><i class="far fa-edit"></i></button>
                        <button class="delete"><i class="far fa-trash-alt"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>Attraction alpha</td>
                    <td>En marche</td>
                    <td>50 <span>Euro</span></td>
                    <td>Enfants + Adultes</td>
                    <td>10 à 12 ans</td>
                    <td>02/07/2021</td>
                    <td>Jhon Doe</td>
                    <td>
                        <button class="edit"><i class="far fa-edit"></i></button>
                        <button class="delete"><i class="far fa-trash-alt"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>Attraction alpha</td>
                    <td>En marche</td>
                    <td>50 <span>Euro</span></td>
                    <td>Enfants + Adultes</td>
                    <td>10 à 12 ans</td>
                    <td>02/07/2021</td>
                    <td>Jhon Doe</td>
                    <td>
                        <button class="edit"><i class="far fa-edit"></i></button>
                        <button class="delete"><i class="far fa-trash-alt"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>Attraction alpha</td>
                    <td>En marche</td>
                    <td>50 <span>Euro</span></td>
                    <td>Enfants + Adultes</td>
                    <td>10 à 12 ans</td>
                    <td>02/07/2021</td>
                    <td>Jhon Doe</td>
                    <td>
                        <button class="edit"><i class="fas fa-pencil-alt"></i></button>
                        <button class="delete"><i class="far fa-trash-alt"></i></button>
                    </td>
                </tr>
              
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