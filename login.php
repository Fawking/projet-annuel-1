<?php
require_once $_SERVER['DOCUMENT_ROOT']."/config.php";
ob_start();
session_start();
include SITE_ROOT.'/db_connect.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<title>Se connecter</title>
<?php include 'global_head_tags.php'; ?>
</head>
<body>
<?php
$page_name = "se_connecter";
include 'nav.php';
?>
<div class="wrapper">
    <form method="post" class='login_form' id="login_form">
            <h1>Se connecter</h1>
            <?php
    if(isset($_POST['submit'])){

        if(!isset($_POST['email']) || !isset($_POST['mot_de_passe']) || empty($_POST['email']) || empty($_POST['mot_de_passe'])){
            echo "<div class='error'><p>Veuillez remplir tous les champs!</p></div>";
        }else{
   
                $stmt=$con->prepare("SELECT id,nom_complet,email,adresse,pays,ville,mot_de_passe FROM utilisateurs WHERE email=?");
            $stmt->bind_param('s',$_POST['email']);
            $stmt->execute();
            $stmt->store_result();
            if($stmt->num_rows()>0){ 
                $stmt->bind_result($Id,$Nom_complet, $Email, $Adresse, $Pays, $Ville, $Mot_de_passe);
                $stmt->fetch(); 
                if(!password_verify($_POST['mot_de_passe'],$Mot_de_passe)){
                    echo "<div class='error'><p>Mot de passe incorrect, veuillez réessayer!</p></div>";
                    $mot_de_passe_incorrect=1;
                }else{
                session_regenerate_id();
                $_SESSION['logged_in'] = TRUE;
                $_SESSION['type_compte'] = "client";
                $_SESSION['id_utilisateur'] = $Id;
                $_SESSION['nom_complet'] = $Nom_complet;
                $_SESSION['email'] = $Email;
                $_SESSION['adresse'] = $Adresse;
                $_SESSION['pays'] = $Pays;
                $_SESSION['ville'] = $Ville;
                header("Location: /");
                exit();
                }
            }else{
                $stmt=$con->prepare("SELECT id, email, username, mot_de_passe FROM admins WHERE email=?");
                $stmt->bind_param('s',$_POST['email']);
                $stmt->execute();
                $stmt->store_result();
                if($stmt->num_rows()>0){ 
                    $stmt->bind_result($Id, $Email, $Username, $Mot_de_passe);
                    $stmt->fetch(); 
                    if(!password_verify($_POST['mot_de_passe'],$Mot_de_passe)){
                        echo "<div class='error'><p>Mot de passe incorrect, veuillez réessayer!</p></div>";
                        $mot_de_passe_incorrect=1;
                    }else{
                        session_regenerate_id();
                        $_SESSION['logged_in'] = TRUE;
                        $_SESSION['type_compte'] = "admin";
                        $_SESSION['id_admin'] = $Id;
                        $_SESSION['username'] = $Username;
                        $_SESSION['email'] = $Email;
                        header("Location: /admin");
                        exit();
                    }
                }
                else{
                    echo "<div class='info'><p>Compte introuvable, vous pouvez vous enregistrer <a href='register'>en cliquant ici</a></p></div>";
                }
            }
 
        }


    }
            ?>
        <div class="field">
            <label for="email">Email</label>
            <input type="text" name="email" id="email" placeholder="EX: utilisateur@email.com" <?php if(isset($mot_de_passe_incorrect, $_POST['email'])) echo "value='".$_POST['email']."'"; ?> required/>
        </div>
        <div class="field">
            <label for="mot_de_passe">Mot de passe</label>
            <input type="password" name="mot_de_passe" id="mot_de_passe" required/>

            <button type="submit" name="submit">Je me connecte</button>
            <a class='mot_de_passe_oublie' href='/forgot_password'>Mot de passe oublié ?</a><br/>
        </div>

        <p class='pas_encore_inscrit'>
        
        Vous n'avez pas de compte ? <button onclick="window.location.href='/register'">Cliquez ici pour vous enregistrer</button>
</p>
    </form>

<?php include SITE_ROOT.'/footer.php'; ?>
</div>

</body>
</html>