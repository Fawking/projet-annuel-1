<?php
require_once $_SERVER['DOCUMENT_ROOT']."/config.php";

include SITE_ROOT.'/db_connect.php';


if(isset($_GET['query'])){
    $query = "%".$_GET['query']."%";

    $sql = "SELECT id, nom, type, age, photo, état FROM attractions WHERE nom LIKE ? OR description LIKE ? ORDER BY créé DESC LIMIT 100";
    if($stmt = $con->prepare($sql)){
    $stmt->bind_param("ss", $query, $query);
if($stmt->execute()){
    $result = $stmt->get_result();
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            echo "<div class='attraction' onclick='window.open(\"attraction?id=".$row['id']."\")'>
            <img src='".$row['photo']."' alt='".$row['nom']."'/>
            <div class='text'>";
            if($row['état'] == "En marche"){
                echo "<h4 class='disponible'>Disponible</h4>";
            }else{
                echo "<h4 class='non_disponible'>Non disponible</h4>";
            }
            echo "<h2>".$row['nom']."</h2>
            <h3>".$row['age']."</h3>
            </div> 
            </div>"; 
        }

    }else{
        echo "0";
    }
    }else{
        echo "error";
    }
}
}
?>
