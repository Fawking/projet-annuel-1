<?php
require_once $_SERVER['DOCUMENT_ROOT']."/config.php";
ob_start();
session_start();
include SITE_ROOT.'/db_connect.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<title>Réinitialisation du mot de passe</title>
<?php include 'global_head_tags.php'; ?>
</head>
<body>
<?php
$page_name = "reinitialisation_du_mot_de_passe";
include 'nav.php';
?>
<div class="wrapper">
    <form method="post" class='contact_form' id="reset_password_form">
        <h1>Réinitialisation du mot de passe</h1>

<?php
if(!isset($_GET['email']) || empty($_GET['email']) || !isset($_GET['code']) || empty($_GET['code'])){
    echo "<div class='error'><p>Erreur : lien non valide</p></div>";
}else{
    $stmt=$con->prepare("SELECT used, expire FROM reset_password_requests WHERE email=? AND code=?");
	$stmt->bind_param('ss',$_GET['email'],$_GET['code']);
	$stmt->execute();
	$stmt->store_result();
	if($stmt->num_rows>0){
        $stmt->bind_result($Used, $Expire);
        $stmt->fetch();
        $now_datetime = new DateTime();
        $Expire_datetime = new Datetime($Expire);
        if($Used){
            echo "<div class='error'><p>Ce lien a déjà été utilisé pour réinitialiser le mot de passe, veuillez <a href='/forgot_password'>cliquer ici</a> pour soumettre à nouveau votre demande</p></div>";
        }elseif($Expire_datetime < $now_datetime){
            echo "<div class='error'><p>Ce lien a expiré (48 heures après la demande), veuillez <a href='/forgot_password'>cliquer ici</a> pour soumettre à nouveau votre demande</p></div>";
        }else{
            //valid link
            if(isset($_POST['submit'])){
                if(!isset($_POST['mot_de_passe'])||empty($_POST['mot_de_passe']) || !isset($_POST['confirmation_mot_de_passe']) || empty($_POST['confirmation_mot_de_passe']) ){
            echo "<div class='error'><p>Veuillez remplir tous les champs !</p></div>";
            }elseif($_POST['mot_de_passe'] != $_POST['confirmation_mot_de_passe']){
                echo "<div class='info'><p>Les deux mots de passe ne correspondent pas!</p></div>";
            }else{
                $password = password_hash($_POST['mot_de_passe'],PASSWORD_DEFAULT);
                $stmt=$con->prepare("UPDATE utilisateurs SET mot_de_passe=? WHERE email=?");
                $stmt->bind_param('ss',$password,$_GET['email']);
                if($stmt->execute()){
                    $used = 1 ;
                    $update_request_used = $con->prepare("UPDATE reset_password_requests SET used=? WHERE email=? AND code=? ");
                    $update_request_used->bind_param("iss",$used, $_GET['email'], $_GET['code']);
                    $update_request_used->execute();
                    echo "<div class='success'><p>Mot de passe mis à jour avec succès! <a href='/login'>cliquez ici</a> pour vous connecter</p></div>";
                }else{
                    echo "<div class='error'><p>Une erreur s'est produite lors de la mise à jour de votre mot de passe, veuillez réessayer !</p></div>";
                }
            
            }            
                    }else{
                    ?>
            <div class='field'>
			<label for="mot_de_passe">Nouveau mot de passe</label>
			<input id="mot_de_passe" name="mot_de_passe" type="password" required />
            </div>
            <div class='field'>
			<label for="confirmation_mot_de_passe">Confirmation du nouveau mot de passe</label>
			<input id="confirmation_mot_de_passe" name="confirmation_mot_de_passe" type="password" required />
            <button type="submit" name="submit">Réinitialiser</button>
            </div>


		</form>
        <?php
                    }
        }
    }else{
        echo "<div class='error'><p>Lien incorrect, veuillez <a href='/forgot_password'>cliquer ici</a> pour soumettre à nouveau votre demande </p></div>";
    }


}
?>
    </form>
    <?php include SITE_ROOT."/footer.php"; ?>
</div>

</body>
</html>