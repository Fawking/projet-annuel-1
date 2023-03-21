<div class='header'>
<nav id="nav">
    <a href="javascript:void(0);" class="icon" onclick="responsiveNavbar()"><i class="fas fa-bars"></i></a>
	<div class="logo" onclick="window.location.href='/'">
        <!--img src='img/logo' alt="logo" /-->
        <h1>attractions<i class="fas fa-circle"></i></h1>
    </div>
    <?php if(isset($page_name) && $page_name == "acceuil"){ ?>
        <div class='search_bar'>
            <input type="text" placeholder="Rechercher.." onkeyup="afficherResultat(this.value)" id="search_input" <?php if(isset($_GET['query'])){ echo "value='".$_GET['query']."'";}?>/>
        </div>
    <?php } ?>
    <div class='links'>
        <a <?php if(isset($page_name) && $page_name == "acceuil") echo "class='active'"; ?> href='/'>Acceuil</a>
        <a <?php if(isset($page_name) && $page_name == "contact") echo "class='active'"; ?> href='/contact' href=''>Contact</a>
        <a <?php if(isset($page_name) && $page_name == "qui_sommes_nous") echo "class='active'"; ?> href='/aboutus'>Qui somme nous</a>

    </div>
    

<?php  if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==TRUE){
       if($_SESSION['type_compte']=="client"){ ?>
        <button class='reserve' onclick="window.location.href='/myreservations'"><i class='far fa-calendar-check'></i>&nbsp;&nbsp;Mes réservations</button>
        <div class="dropdown">
        <button  class='account'><img class='avatar' src='https://ui-avatars.com/api/?name=<?php echo $_SESSION['nom_complet']; ?>&rounded=true&bold=true'/></button>

<?php  }else{ ?>
        <button class='dashboard_link' onclick="window.location.href='/admin/'"><i class='fa fa-toolbox'></i>&nbsp;&nbsp;Tableau de bord</button>
        <div class="dropdown">
        <!--button class='account'><img src="/img/blank-profile-picture"/></button-->
        <button  class='account'><img class='avatar' src='https://ui-avatars.com/api/?name=<?php echo $_SESSION['username']; ?>&rounded=true&bold=true'/></button>

    <?php } ?>


    <div class="dropdown-content">
        <a href='/logout'> <i class='fa fa-sign-out-alt'>&nbsp;&nbsp;</i>Se déconnecter</a>
        </div>
    </div>
<?php
    }else{
    ?>
       <button class='signin' onclick="window.location.href='/login'"><i class='fa fa-sign-in-alt'></i>&nbsp;&nbsp;Se connecter</button>
    <?php
        
    }
   ?>

</nav>
</div>
