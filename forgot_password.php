<?php
require_once $_SERVER['DOCUMENT_ROOT']."/config.php";
ob_start();
session_start();
include SITE_ROOT.'/db_connect.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<title>Demande de réinitialisation du mot de passe</title>
<?php include 'global_head_tags.php'; ?>

</head>
<body>
<?php
$page_name = "mot_de_passe_oublie";
include 'nav.php';
?>
<div class="wrapper">
    <form method="post" class='contact_form' id="forgot_password_form">
        <h1>Demande de réinitialisation du mot de passe</h1>

<?php
if(isset($_POST['submit'])){

    if(!isset($_POST['email'])||empty($_POST['email'])){
		echo "<div class='info'>Veuillez entrer un e-mail!</div>";
		}
        elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            echo "<div class='info'><p>Veuillez entrer un e-mail valide!</p></div>";
        }
            else{


                $stmt=$con->prepare("SELECT nom_complet FROM utilisateurs WHERE email=?");
                $stmt->bind_param('s',$_POST['email']);
                $stmt->execute();
                $stmt->store_result();
                if($stmt->num_rows>0){
                    $stmt->bind_result($Nom_complet);
                    $stmt->fetch();
                    $code = uniqid();
                    $expire = new DateTime();
                    $expire = $expire->add(new DateInterval('PT48H'));
                    $expire_string = $expire->format('Y-m-d H:i:s');
                    $not_used = 0;
                    $link = WEBSITE_URL."/reset_password?code=".$code."&email=".$_POST['email'];
                    $insert_request = $con->prepare("INSERT INTO reset_password_requests (email, code, expire, used) VALUES (?, ?, ?, ?)");
                    $insert_request->bind_param('sssi',$_POST['email'], $code, $expire_string, $not_used);
                    if($insert_request->execute()){
                    
                

    $from = MAILER_SENDER;
    $to = $_POST['email'];
   // $subject = "Réinitilisation du mot de passe de ".WEBSITE_URL;
    $subject = "Réinitilisation du mot de passe de Attractions";
    $message = "<html>
    <head>

    <link rel='preconnect' href='https://fonts.googleapis.com'>
    <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
    <link href='https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap' rel='stylesheet'>
    <style>
    .email_container{
        padding:35px;
        font-size:16px;
        line-height:2;
    }
    h1{
        font-size:30px;
        color: #002037;
        text-align: center;
        margin: 15px auto 10px auto;
        font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    }
    h2{
        font-size:20px;
    }
    p{
        margin:20px auto 20px auto;
        
    }
    button{
        font-weight: 600;
        background-color: #30b862;
        color: white;
        padding: 15px 35px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        margin: 0px auto 20px auto;
        font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;

    }
    button:hover{
        opacity:0.8;
    }
    button a, .ii a[href]{
        padding: 15px 35px;
        color:white !important;
        text-decoration:none;
    }
    img{
        float:right;
        max-width:90px;
        height:auto;
        margin:15px 15px 15px 15px;
    }

    </style>
    </head>


    <body>
    <div class='email_container'>
    <h1>Réinitialisation du mot de passe de ".WEBSITE_URL."</h1>
    <h2>Bonjour ".$Nom_complet.",</h2>
    <p>Nous venons de recevoir une demande de réinitialisation de votre mot de passe sur ".WEBSITE_URL." et nous sommes là pour vous aider !
    Cliquez simplement sur le bouton ci-dessous pour créer un nouveau mot de passe pour votre compte : </p>
    <button><a href='".$link."'>Réinitialiser</a></button>
    <p>
    Le bouton n'a pas fonctionné ? cliquez sur ce lien ou copiez collez-le dans votre barre d'adresse :
    <a href='".$link."'>".$link."</a>
    <br/>
    <br/>
    Si vous rencontrez des problèmes pour réinitialiser votre mot de passe, vous pouvez nous contacter sur ".CONTACT_EMAIL.".
  <br/>
  Salutations,</p>
    </div>
    </body>
    </html>";


$mail = new PHPMailer(true);

try {

    $mail->SMTPDebug = 0;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = MAILER_HOST;                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = MAILER_USERNAME;                     //SMTP username
    $mail->Password   = MAILER_PASSWORD;                               //SMTP password

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = MAILER_PORT;     
    
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';
    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    //$mail->setFrom(CONTACT_EMAIL, 'Attractions');
   // $mail->setFrom($from, $_POST['nom_complet']);
    $mail->setFrom(MAILER_SENDER, "Attractions");
    $mail->addAddress($to);

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;

    $mail->send();
    echo "<div class='info'><p>Un lien de réinitialisation a été envoyé à ".$_POST['email']."</p></div>";
} catch (Exception $e) {
    echo "<div class='error'><p>Une erreur s'est produite veuillez réesayer, si le problème persiste veuillez nous contacter par email <a href='mailto:".CONTACT_EMAIL."'>".CONTACT_EMAIL."</a></p></div>";

}


}else{
    echo "<div class='error'><p>Une erreur s'est produite lors de l'envoi du lien de réinitialisation du mot de passe, veuillez réessayer !</p></div>";
}

}else{
    echo "<div class='info'><p>Compte introuvable !</p></div>";
}
            }

}
?>

        <div class="field">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required/>
            <button type="submit" name="submit">Envoyer</button>
        </div>

    </form>
    <?php include SITE_ROOT."/footer.php"; ?>
</div>

</body>
</html>