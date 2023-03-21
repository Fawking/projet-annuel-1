<?php
require_once $_SERVER['DOCUMENT_ROOT']."/config.php";
ob_start();
session_start();
include SITE_ROOT.'/db_connect.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<title>Réserver</title>
<?php include 'global_head_tags.php'; ?>
</head>
<body>
<?php
$page_name = "reserver";
include 'nav.php';

?>
<div class="wrapper">

    <?php
    if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==TRUE){
        if($_SESSION['type_compte']=="client"){
 
    if(isset($_GET['id']) && !is_nan($_GET['id'])){
        $id_attraction = $_GET['id'];
        $stmt = $con->prepare("SELECT nom, prix, état FROM attractions WHERE id=?");
        $stmt->bind_param("i", $id_attraction);
        if($stmt->execute()){
            $stmt->store_result();
            if($stmt->num_rows > 0){
                $stmt->bind_result($Nom, $Prix, $Etat);
                $stmt->fetch();
                if($Etat == "En marche"){
                    if(isset($_POST['submit_1'])){
                        $booking_exists_stmt = $con->prepare("SELECT id FROM reservé WHERE id_attraction = ? AND date = ?");
                        $booking_exists_stmt->bind_param("is", $_GET['id'], $_POST['date']);
                        if($booking_exists_stmt->execute()){
                            $booking_exists_stmt->store_result();
                            if($booking_exists_stmt->num_rows > 0){
                                ?>
                                <div class="checkout_page_container">
                                    <?php
                            echo "<div class='info'><p>Cette attraction est déja réservé le ".$_POST['date'].", veuillez choisir une autre date</p></div>";
                            ?> </div> <?php
                            }else{
                            ?>
                        <div class="checkout_page_container">
                            <h2>Réservez ici</h2>
                            <h1><?php echo $Nom; ?></h1>
                            <?php $tarifs_ticket = number_format((float) doubleval($_POST['nb_tickets'])*$Prix, 2, '.', ''); ?>
                            <?php $total = floatval($tarifs_ticket)?>
                            <h4>Total : € <?php echo number_format($total, 2, '.', ''); ?></h4>
                            
                                <form method="post" class='reserve_form_2' id="checkoutForm">
        
                               
                                    <div class='multiple_field'>
                                        <label>Informations de carte</label>
                                        <input type="text" name="code" id="code" placeholder="Ex: 1234 1234 1234 1234"  data-stripe="number" required/>
                                        <div class='inline'>
                                            <div class='field'>
                                                <input type="text" name="expiration" placeholder="MM/YY" id="expiration"  required/>
                                                <input type="hidden" id="exp_month" name="exp_month" data-stripe="exp_month"/>
                                                <input type="hidden" id="exp_year" name="exp_year"  data-stripe="exp_year"/>
        
                                            </div>
                                            <div class='field'>
                                                <input type="text" name="cvc" placeholder="CVC"  data-stripe="cvc" required/>
                                                <input type='hidden' data-stripe="locale" value='fr'/>
                                            </div>
                                        </div>
                                    </div>
                <div class='field'>
                    <label>Nom sur la carte</label>
                    <input type="text" name="nom_en_carte" required/>
                </div>
                <div class='multiple_field'>
                    <label>Informations de facturation</label>
                    <input type="text" name="adresse1" placeholder="Ligne d'adresse 1" required/>
                    <input type="text" name="adresse2" placeholder="Ligne d'adresse 2" required/>
                    <div class='inline not_last'>
                        <div class='field'>
                            <input type="text" name="ville" placeholder="Ville" required/>
                        </div>
                        <div class='field'>
                            <input type="text" name="code_zip" placeholder="Code ZIP" required/>
                        </div>
                    </div>
                    <input type="text" name="etat" placeholder="Etat" required/>
                    <input type="text" name="pays" placeholder="Pays" required/>
                </div>
                <div class='field' id="bookingValidation">
                   
                </div>
                <div class="field last">
                    <input type="hidden" name="nb_adultes" value="<?php echo $_POST['nb_adultes']; ?>" />
                    <input type="hidden" name="nb_enfants" value="<?php echo $_POST['nb_enfants']; ?>" />
                    <input type="hidden" name="nb_tickets" value="<?php echo $_POST['nb_tickets']; ?>" />
                    <input type="hidden" name="date" value="<?php echo $_POST['date']; ?>" />
                   
                    <button type="submit" name="submit_2" id="submitButton"><i class='fa fa-lock'></i> Payer mes tickets</button>
        
                </div>

        
               
            </form>
            
        
         </div>
        
         <?php
                    }
                        }
    
                    }else{
                        ?>
                        <form method="post" class='reserve_form'>
                        <?php
                        if(isset($_POST['submit_2'])){
                            if(!isset($_POST['nb_enfants'],$_POST['nb_adultes'], $_POST['nb_tickets'], $_POST['date'], $_POST['adresse1'], $_POST['adresse2'], $_POST['ville'], $_POST['pays'], $_POST['etat'], $_POST['code_zip']) || empty($_POST['nb_enfants']) || empty($_POST['nb_adultes']) || empty($_POST['nb_tickets']) || empty($_POST['date']) || empty($_POST['adresse1']) || empty($_POST['adresse2']) || empty($_POST['ville']) || empty($_POST['pays']) || empty($_POST['etat']) || empty($_POST['code_zip']|| is_nan($_POST['nb_tickets']) || intval($_POST['nb_tickets'])<1)){
                                echo "<div class='error'><p>Veuillez remplir tous les champs!</p></div>";
                            }else{
    
                                $total_to_pay = (floatval($Prix)*$_POST['nb_tickets'])*100;
                                
                                $stmt = $con->prepare("INSERT INTO reservé (id_utilisateur, id_attraction, nb_adultes, nb_enfants, nb_tickets, date, adresse1, adresse2, pays, ville, état, code_zip, montant_payé) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? , ?)");
                                $stmt->bind_param("iiiiisssssssd", $_SESSION['id_utilisateur'], $_GET['id'], $_POST['nb_adultes'], $_POST['nb_enfants'], $_POST['nb_tickets'], $_POST['date'], $_POST['adresse1'], $_POST['adresse2'], $_POST['pays'], $_POST['ville'], $_POST['etat'], $_POST['code_zip'], $paid_amount);
                                
                                                         
                                        $paid_amount = $total_to_pay/100;
                                        if($stmt->execute()){
                                            echo "<div class='success'><p>Réservation effectuée avec succés!</p></div>";
                                        }else{
                                            echo "<div class='error'><p>Une erreur s'est produite, veuillez réessayer!</p></div>";
                                        }
 
                            }

                    }
                        ?>
                        
                        <h2>Réservez ici</h2>
                        <h1><?php echo $Nom; ?></h1>
                        <h4>Prix du ticket : €<?php echo number_format($Prix, 2, '.', ''); ?></h4>
            
                       
                    <div class='inline'>
                        <div class='field'>
                            <label for="nb_adultes">Nombre d'adultes</label>
                            <input type="number" name="nb_adultes" id="nb_adultes" step="1" min="0" max="100" value="1" required/>
                        </div>
                        <div class='field'>
                            <label for="nb_enfants">Nombre d'enfants</label>
                            <input type="number" name="nb_enfants" id="nb_enfants" step="1" min="1" max="100" value="1" required/>
                        </div>
                    </div>
                    <div class='inline'>
                        <div class='field'>
                            <label for="nb_tickets">Nombre de tickets</label>
                            <input type="number" name="nb_tickets" id="nb_tickets" step="1" min="1" max="500" value="1" required/>
                        </div>
                        <div class='field'>
                        <label for="date">Date</label>
                        <?php
                        $now_date = date("Y-m-d");
                        ?>
                        <input type="date" name="date" id="date" min="<?php echo $now_date; ?>" required/>
            
                        </div>
                    </div>
                    <div class="field">
                        <button type="submit" name="submit_1">Suivant</button>
                    </div>
                </form>
                <?php
                    }
                }else{
                    echo "<div class='checkout_page_container'>
                    <div class='info'><p>Cette attraction n'est pas disponible pour le moment</p></div>
                    </div>";
                }
              
            }
        }
    }
}else{
    echo "<div class='error'><p>Erreur : Cette page est réservée aux clients uniquement</p></div>";
}
}else{
header("Location: /login");
}
                ?>



<?php include SITE_ROOT.'/footer.php'; ?>
</div>

<?php if($stmt->num_rows > 0 && !isset($_POST['submit_1']) && !isset($_POST['submit_2'])){
?>
<script>
    $('#nb_tickets').on('change', ()=>{
        if($('#nb_tickets').val() < 0){
            $('#nb_tickets').val(1)
        }else{
            value = $('#nb_tickets').val();
            $('h4').text('Total : € '+(value*<?php echo $Prix; ?>).toFixed(2));
        }
        
    })
</script>
<script>
    $(document).ready(()=>checkIfBooked())
    function checkIfBooked() {
    $.ajax({
        method: "GET",
        url: "check_if_booking_exists_ajax.php?id_attraction=<?php echo $_GET['id'] ?>&date="+$("#date").val(),
        }).done(function(response) {
            $(".reserve_form").find(".info").remove()
            if(response == "1"){
                $("<div class='info'><p>Cet emplacement est déjà pris veuillez choisir une autre date</p></div>").insertBefore($(".reserve_form button"));
                $(".reserve_form button").attr("type", "button")
            }else{

                $(".reserve_form button").attr("type", "submit")
            }
            }).fail(function(xhr, status, error) {
    })
  
}
    $("#date").on("change", ()=>{
        checkIfBooked()
    })

</script>
<?php
}
?>

</body>
</html>