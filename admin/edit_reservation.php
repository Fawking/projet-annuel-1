<?php
require_once $_SERVER['DOCUMENT_ROOT']."/config.php";
ob_start();
session_start();
include SITE_ROOT.'/db_connect.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<title>Tableau de bord : Modifier réservation</title>
<?php include SITE_ROOT.'/global_head_tags.php'; ?>
</head>
<body>
<?php
$page_name = "reservations";
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
                $id_reservation = $_GET['id'];

                if(isset($_POST['submit'])){
                    $update_stmt = $con->prepare("UPDATE reservé SET date = ? WHERE id=?");
                    $update_stmt->bind_param("si", $_POST['date'], $id_reservation);
                    if($update_stmt->execute()){
                        echo "<div class='success'><p>Réservation mise à jour avec succès !</p></div>";
                    }else{
                        echo "<div class='error'><p>Une erreur s'est produite veuillez réessayer!</p></div>";
                    }
                }

                $stmt = $con->prepare("SELECT date FROM reservé WHERE id=?");
                $stmt->bind_param("i", $id_reservation);
                if($stmt->execute()){
                    $stmt->store_result();
                    $stmt->bind_result($Date);
                    $stmt->fetch();
                    ?>
                    <div class="edit_form">
                        <form method="post" enctype="multipart/form-data">
                        <h1>Modifier réservation</h1>
                        
                            <div class='field'>
                                <label for="date">Date</label>
                                <?php
                                $now_date = date("Y-m-d");
                                ?>
                                <input type="date" name="date" id="date" min="<?php echo $now_date; ?>" value="<?php echo $Date; ?>" required/>
                                <button type="submit" name="submit">Valider</button>
                            </div>
                            
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