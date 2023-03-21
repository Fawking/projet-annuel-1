<?php
require_once $_SERVER['DOCUMENT_ROOT']."/config.php";
ob_start();
session_start();
include SITE_ROOT.'/db_connect.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<title>Tableau de bord : Modifier attraction</title>
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
            <?php
            if(isset($_GET['id']) && !empty($_GET['id']) && !is_nan($_GET['id'])){
                $id_attraction = $_GET['id'];

                if(isset($_POST['submit'])){

                    if (!file_exists($_FILES['photo']['tmp_name']) || !is_uploaded_file($_FILES['photo']['tmp_name'])){
                        $photo_updated = 0;
                    }else{
                        $photo_updated = 1;

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
                            $photo_updated = 0;
                        }

                    }
                            
                           

                                if($photo_updated){
                                    $update_stmt = $con->prepare("UPDATE attractions SET nom = ?, type = ?, prix = ?, age = ?, état = ?, description = ?, photo = ? WHERE id=?");
                                    $update_stmt->bind_param("ssdssssi", $_POST['nom_attraction'], $_POST['type'], $_POST['prix'], $_POST['age'], $_POST['etat'], $_POST['description'], $target_file, $id_attraction);
                                }else{
                                    $update_stmt = $con->prepare("UPDATE attractions SET nom = ?, type = ?, prix = ?, age = ?, état = ?, description = ? WHERE id=?");
                                    $update_stmt->bind_param("ssdsssi", $_POST['nom_attraction'], $_POST['type'], $_POST['prix'], $_POST['age'], $_POST['etat'], $_POST['description'], $id_attraction);
                                }
                                
                                if($update_stmt->execute()){
                                    echo "<div class='success'><p>Attraction mise à jour avec succès !</p></div>";
                                }else{
                                    echo "<div class='error'><p>Une erreur s'est produite veuillez réessayer!</p></div>";
                                }
                }

                $stmt = $con->prepare("SELECT nom, type, age, prix, état, description, photo FROM attractions WHERE id=?");
                $stmt->bind_param("i", $id_attraction);
                if($stmt->execute()){
                    $stmt->store_result();
                    $stmt->bind_result($Nom, $Type, $Age, $Prix, $Etat, $Description, $Photo);
                    $stmt->fetch();
                    
                    ?>
                    <div class="edit_form">
                        <form method="post" enctype="multipart/form-data">
                        <h1>Modifier l'attraction</h1>
                        <div class='field'>
                                <label for="nom_attraction">Nom de l'attraction</label>
                                <input name="nom_attraction" type="text" id="nom_attraction" value="<?php if(!empty($Nom)) echo $Nom ; ?>" required/>
                            </div>
                        <div class='inline'>
                            <div class='field'>
                                <label for="age">Age</label>
                                <select id="age" name="age">
                                    <option <?php if($Age=="10 à 12 ans") echo "selected"; ?>>10 à 12 ans</option>
                                    <option <?php if($Age=="Moins de 10 ans") echo "selected"; ?>>Moins de 10 ans</option>
                                </select>
                            </div>
                            <div class='field'>
                                <label for="type">Type d'attraction</label>
                                <select name="type" id="type" required>
                                    <option <?php if($Type=="Enfants") echo "selected"; ?>>Enfants</option>
                                    <option <?php if($Type=="Enfants + Adultes") echo "selected"; ?>>Enfants + Adultes</option>
                                </select>
                            </div>
                        </div>
        
                        <div class='inline'>
                            <div class='field'>
                                <label for="prix">Prix</label>
                                <input name="prix" type="number" id="prix" min="0" step="0.01" value="<?php if(!empty($Prix)) echo $Prix ; ?>" required>
                                </input>
                            </div>
                            <div class='field'>
                                <label for="etat">Etat</label>
                                <select name="etat" id="etat" required>
                                    <option <?php if($Etat=="En marche") echo "selected"; ?> >En marche</option>
                                    <option <?php if($Etat=="Suspendue") echo "selected"; ?> >Suspendue</option>
                                </select>
                            </div>
                        </div>
                <div class="field">
                <label for="description">Description</label>
                        <textarea name="description" id="description" required ><?php if(!empty($Description)) echo $Description ; ?></textarea>
                    <label>Photo actuelle</label>
                    <div class='photo_actuelle'>
                        <img src="<?php echo $Photo; ?>"/>
                    </div>
                    <label for="photo">Nouvelle Photo</label>
                    <span>(veuillez ne rien uploader si vous ne voulez pas la mettre à jour)</span>
                    <input type="file" name="photo" id="photo" accept="image/*" />
                    <button type="submit" name="submit">Valider</button>
                </div>
                        </form>
                    </div>
        
                </div>
                <?php  
                }
            }else{
                echo "<div class='error'><p>Erreur : lien invalide</p></div>";
            }
            
           
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
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
<script src='/index.js'></script>

</body>
</html>