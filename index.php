<?php
require_once $_SERVER['DOCUMENT_ROOT']."/config.php";
ob_start();
session_start();
include SITE_ROOT.'/db_connect.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<title>Attractions</title>
<meta name="description" content="Attractions">
<?php include 'global_head_tags.php'; ?>
</head>
<body>

<div class="wrapper">
<?php $page_name = "acceuil"; include 'nav.php'; ?>
<div class='attractions'>
    <?php
                    if (!isset ($_GET['page']) || $_GET['page']<1) {  
                        $current_page = 1;  
                    } else {
                        $current_page = $_GET['page'];  
                    } 
                    $results_per_page = NUMBER_OF_RESULTS_PER_PAGE;  
    
                    $page_first_result = ($current_page-1) * $results_per_page; 
                    $tous_les_attractions = $con->query("SELECT id FROM attractions");
                    $nombre_de_tous_les_attractions = $tous_les_attractions->num_rows;

                    $number_of_pages = ceil ($nombre_de_tous_les_attractions  / $results_per_page);
    
                    $resultat_attractions = $con->query("SELECT * FROM attractions ORDER BY créé DESC LIMIT ".$page_first_result.",".$results_per_page);
                    if($resultat_attractions->num_rows > 0){
                       
                        while($attraction = $resultat_attractions->fetch_assoc()){
                            echo "<div class='attraction' onclick='window.open(\"attraction?id=".$attraction['id']."\")'>
                            <img src='".$attraction['photo']."' alt='".$attraction['nom']."'/>
                            <div class='text'>";
                            if($attraction['état'] == "En marche"){
                                echo "<h4 class='disponible'>Disponible</h4>";
                            }else{
                                echo "<h4 class='non_disponible'>Non disponible</h4>";
                            }
                            echo "<h2>".$attraction['nom']."</h2>
                            <h3>".$attraction['age']."</h3>
                            </div> 
                            </div>"; 
                        }
                    }

    ?>

</div>
                <div class="pagination">
                <?php
                if($current_page >1){ 
                    echo "<a href='?page=1'>&laquo;</a>";
                }
                for($page = 1; $page<= $number_of_pages; $page++) {  
                    if($page==$current_page){
                        echo '<a class="active" href="?page='.$page.'">'.$page.' </a>';
                    }else{
                        echo '<a href="?page='.$page.'">'.$page.' </a>';
                    }
                }
                if($current_page < $number_of_pages){ 
                    echo '<a href="?page='.$number_of_pages.'">&raquo;</a>';
                } 
                ?>
            </div>
            <?php include 'footer.php'; ?>
</div>
<script>
    var current_content;
    $(document).ready(()=>{
        current_content = $(".attractions").html();
        pagination_content = $(".pagination").html();
        afficherResultat($("#search_input").val())
    })
    const getQueryParameter = (param) => new URLSearchParams(document.location.search.substring(1)).get(param);
function afficherResultat(str) {
    $(".attractions").html("<img class='spinner' src='/img/Rolling-0.8s-200px.gif'/>");
  if (str.length==0) {
    $(".attractions").html(current_content);
    $(".pagination").html(pagination_content)
    return;
  }else if(str.length <3){
      $(".attractions").html("<img class='spinner' src='/img/Rolling-0.8s-200px.gif'/>");
      $(".pagination").html("");
  }
  else{
    $(".pagination").html("");
    $.ajax({
        method: "GET",
        url: "search.php?query="+str,
        }).done(function(response) {
            if(response == "0"){
                $(".attractions").html("<p>Aucun résultat correspend à votre recherche</p>");
            }else{
                $(".attractions").html(response);
            }
            }).fail(function(xhr, status, error) {
                $(".attractions").html("<p>Aucun résultat correspend à votre recherche</p>");
    })
  }
}
</script>
</body>
</html>