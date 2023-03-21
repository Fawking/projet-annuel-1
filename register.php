<?php
require_once $_SERVER['DOCUMENT_ROOT']."/config.php";
ob_start();
session_start();
include SITE_ROOT.'/db_connect.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<title>S'inscrire</title>
<?php include 'global_head_tags.php'; ?>
</head>
<body>
<?php
$page_name = "inscrire";
include 'nav.php';
?>
<div class="wrapper">
    <form method="post" class='register_form' id='register_form'>
            <h1>S'inscrire</h1>
<?php
if(isset($_POST['submit'])){
    if(!isset($_POST['nom_complet'],$_POST['email'],$_POST['adresse'],$_POST['pays'],$_POST['mot_de_passe'])
	|| empty($_POST['nom_complet']) || empty($_POST['email']) || empty($_POST['adresse'])
    || empty($_POST['pays']) || empty($_POST['mot_de_passe'])){
		echo "<div class='info'>Veuillez remplir tous les champs!</div>";
		}
        elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            echo "<div class='info'><p>Veuillez entrer un e-mail valide!</p></div>";
        }
		elseif(strlen($_POST['mot_de_passe']) < 4){
			echo "<div class='info'>Mot de passe trop court!</div>";
		}
		elseif(strlen($_POST['mot_de_passe']) > 45){ 
			echo "<div class='info'>Mot de passe trop long, la taille maximal est de 45 caractères!</div>";
		}
		elseif($_POST['confirmation_mot_de_passe'] != $_POST['mot_de_passe']){
		echo "<div class='info'>Les deux mots de passe ne correspondent pas, veuillez réessayer!/div>";
		}
        else{
            $stmt=$con->prepare("SELECT id from utilisateurs WHERE email=?");
            $stmt->bind_param('s',$_POST['email']);
            $stmt->execute();
            $stmt->store_result();
            if($stmt->num_rows > 0){
                echo "<div class='info'>Cet email est déjà enregistré, cliquez <a href='login'>ici</a> pour vous connecter!</div>";
            }
            else{
                $stmt=$con->prepare("INSERT INTO utilisateurs (nom_complet,email,adresse,pays,ville,mot_de_passe) VALUES (?,?,?,?,?,?)");
                $password = password_hash($_POST['mot_de_passe'],PASSWORD_DEFAULT);
				$stmt->bind_param("ssssss",$_POST['nom_complet'],$_POST['email'],$_POST['adresse'],$_POST['pays'],$_POST['ville'],$password);
				if($stmt->execute()){
                    echo "<div class='success'><p>Vous avez inscrit avec succés, cliquez <a href='login'>ici</a> pour vous connecter</p></div>";
                }else{
                    echo "<div class='error'><p>Une erreur s'est produite lors de l'inscription, veuillez réessayer!</p></div>";
                }
            }
        }

}
?>
        <div class="field">
            <label for="nom_complet">Votre nom complet</label>
            <input type="text" name="nom_complet" id="nom_complet" placeholder="Ex: Jhon Doe" required/>
        </div>
        <div class="field">
            <label for="email">Email</label>
            <input type="text" name="email" id="email" placeholder="Ex: utilisateur@email.com" required/>
        </div>
        <div class="field">
            <label for="adresse">Adresse</label>
            <input type="text" name="adresse" id="adresse" required/>
        </div>
        <div class='inline'>
            <div class='field'>
                <label for="pays">Pays</label>
                <select name="pays" id="pays" required>
                    <option disabled selected></option>
                    <?php include SITE_ROOT.'/tous_les_pays.php'; ?>
                </select>
            </div>
            <div class='field'>
                <label for="ville">Ville</label>

                <input name="ville" id="ville" required/>
            </div>
        </div>
        <div class="field">
            <label for="mot_de_passe" placeholder="Entre 4 et 45 caractères">Mot de passe</label>
            <input type="password" name="mot_de_passe" id="mot_de_passe" required/>
        </div>
        <div class="field">
            <label for="confirmation_mot_de_passe">Confirmez votre mot de passe</label>
            <input type="password" name="confirmation_mot_de_passe" id="confirmation_mot_de_passe" required/>
            <button type="submit" name="submit">Je m'enregistre</button>
        </div>
    </form>
    
<?php include SITE_ROOT.'/footer.php'; ?>
</div>

</body>
</html>