<?php
require_once $_SERVER['DOCUMENT_ROOT']."/config.php";
ob_start();
session_start();
include SITE_ROOT.'/db_connect.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<title>Attraction</title>

<?php include 'global_head_tags.php'; ?>
<link href='fullcalendar/lib/main.css' rel='stylesheet' />
<script src='fullcalendar/lib/main.js'></script>

</head>
<body>
    
<?php $page_name = "attraction"; include 'nav.php'; ?>
<div class="wrapper">
    <?php if(isset($_GET['id']) && !is_nan($_GET['id'])){
        $id_attraction = $_GET['id'];
        $stmt = $con->prepare("SELECT nom, age, état, description, photo FROM attractions WHERE id=?");
        $stmt->bind_param("i", $id_attraction);
        if($stmt->execute()){
            $stmt->store_result();
            if($stmt->num_rows > 0){
                $stmt->bind_result($Nom, $Age, $Etat, $Description, $Photo);
                $stmt->fetch();
                ?>
                <div class='one_attraction'>
        <div class='photo'>
            <img src='<?php echo $Photo; ?>' alt='<?php echo $Nom; ?>'/>
            <div class='text'>
                <?php
                if($Etat == "En marche"){
                    echo "<h4 class='disponible'>Disponible</h4>";
                }else{
                    echo "<h4 class='non_disponible'>Non disponible</h4>";
                    }
                ?>
                <h2><?php echo $Nom; ?></h2>
                <h3><?php echo $Age; ?></h3>
            </div>   
            <?php if($Etat == "En marche"){
            ?>
            <button onclick="window.location.href='reserve?id=<?php echo $id_attraction; ?>';">Réserver ici</button>
            <?php
            }
            ?>
        </div>
        <div class='description'>
            <p><?php echo $Description; ?></p>
        </div>
        <div class='calendrier' id="calendrier">

        </div>
    </div>
    <?php include 'footer.php'; ?>
    <?php
               echo "<script>
               document.title = '".$Nom."';
               </script>";
            }

        }
    }
    ?>
<script>

document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendrier');
  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    contentHeight:"auto",
    events:[
        <?php
        if($stmt->num_rows > 0){
        $resultat_reservations = $con->query("SELECT date, id_utilisateur FROM reservé WHERE id_attraction = ".$_GET['id']." ORDER BY créé DESC LIMIT 200");
        if($resultat_reservations->num_rows > 0){
            while($reservation = $resultat_reservations->fetch_assoc()){
                if(isset($_SESSION['id_utilisateur']) && $reservation['id_utilisateur'] == $_SESSION['id_utilisateur']){
                    echo "{
                        title: 'Réservé par vous',
                        color: 'rgba(50, 237, 50, 0.5)',
                        ";
                }else{
                    echo "{
                        title: 'Réservé',
                        ";
                }
                
                   echo "start: '".$reservation['date']."',
                },
                ";
            }
        }
    }
        ?>
    ],
  });
  calendar.render();
});

</script>
</div>
</body>
</html>