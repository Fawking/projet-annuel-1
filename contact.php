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
<title>Nous contacter</title>
<?php include 'global_head_tags.php'; ?>
</head>
<body>
<?php
$page_name = "contact";
include 'nav.php';
?>
<div class="wrapper">
    <form method="post" class='contact_form' id='contact_form'>
        <h1>Nous contacter</h1>

<?php
if(isset($_POST['submit'])){
    if(!isset($_POST['nom_complet'],$_POST['email'], $_POST['message'], $_POST['sujet'])
	|| empty($_POST['nom_complet']) || empty($_POST['email']) || empty($_POST['message']) || empty($_POST['sujet'])){
		echo "<div class='info'>Veuillez remplir tous les champs!</div>";
		}
        elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            echo "<div class='info'><p>Veuillez entrer un e-mail valide!</p></div>";
        }
            else{
                
    $from = MAILER_SENDER;
    $to = CONTACT_EMAIL;
    $subject = $_POST['sujet'];
    $message = "<html>
    <head>
    <link rel='preconnect' href='https://fonts.googleapis.com'>
    <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
    <link href='https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap' rel='stylesheet'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css'>
    <style>
    .email_container{
        padding:40px;
        font-size:15px;
    }

    h2{
        font-size:22px;
        color: #30b862;
        font-weight:500;
        margin: 15px auto 20px auto;
        font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    }
    h1{
        color:#363636;
        font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        font-size:30px;
        text-align: center;
        margin: 15px auto 40px auto;
    }

    h1 i{
    font-size:10px;
    margin-left: 4px;
    color:#de1b1b;
    }

    h2{
        font-size:17px;
    }
    p{
        font-family: 'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif;
        margin:20px auto 20px auto;
        letter-spacing:1px;
        line-height:2.4;
    }
    b{
        font-weight:500;
    }

    </style>
    </head>
    <body>
    <div class='email_container'>
        
    <h1>attractions<i class='fas fa-circle'></i></h1>
    <h2>Vous avez reçu un nouveau message à partir du formulaire de contact de attractions</h2>
    <p><b>Nom :</b> ".$_POST['nom_complet']."<br/></p>
    <p><b>Email :</b> ".$_POST['email']."</p>
    <p><b>Sujet :</b> ".$_POST['sujet']."</p>
    <p><b>Message : </b><br/>".$_POST['message']."</p>
    <br/>
    <br/>
    </div>
    </body>
    </html>";


//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function


//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {

    $mail->SMTPDebug = 0;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = MAILER_HOST;                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = MAILER_USERNAME;                     //SMTP username
    $mail->Password   = MAILER_PASSWORD;                               //SMTP password

    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = MAILER_PORT;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    //$mail->setFrom(CONTACT_EMAIL, 'Attractions');
   // $mail->setFrom($from, $_POST['nom_complet']);
    $mail->setFrom(MAILER_SENDER, "Attractions");
    $mail->addAddress($to);

    //$mail->addReplyTo(CONTACT_EMAIL, 'Attractions');
    $mail->addReplyTo($from, $_POST['nom_complet']);


    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;

    $mail->send();
    echo "<div class='success'><p>Merci pour votre message, nous ferons de notre mieux pour vous répondre dans les plus brefs délais</p></div>";
} catch (Exception $e) {
    echo "<div class='error'><p>Une erreur s'est produite veuillez réesayer, si le problème persiste veuillez nous contacter par email <a href='".CONTACT_EMAIL."'>".CONTACT_EMAIL."</a></p></div>";
    
}
            }



}
?>
            <?php
            $client_and_loggedin = 0;
            if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==TRUE && $_SESSION['type_compte']=="client"){
                $client_and_loggedin = 1;
            }
            ?>
        <div class="field">
            <label for="nom_complet">Votre nom complet</label>
            <input type="text" name="nom_complet" id="nom_complet"  <?php if($client_and_loggedin && isset($_SESSION['nom_complet'])) echo "value='".$_SESSION['nom_complet']."'"; ?> required/>
        </div>
        <div class="field">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="Ex: utilisateur@email.com"  <?php if($client_and_loggedin && isset($_SESSION['email'])) echo "value='".$_SESSION['email']."'"; ?> required/>
        </div>
        <div class="field">
            <label for="sujet">Sujet</label>
            <input type="text" name="sujet" id="sujet" required/>
        </div>
        <div class="field">
            <label for="message">Message</label>
            <textarea id="message" name='message'></textarea>
            <button type="submit" name="submit"><i class='fa fa-paper-plane'></i>&nbsp;&nbsp;Envoyer</button>
        </div>
    </form>
    <?php include SITE_ROOT."/footer.php"; ?>
</div>

</body>
</html>