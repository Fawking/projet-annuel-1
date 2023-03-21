<div class='admin-header'>
<nav id="nav">
    <a href="javascript:void(0);" class="icon" onclick="responsiveNavbar()"><i class="fas fa-bars"></i></a>
	<div class="logo" onclick="window.location.href='/'">
        <!--img src='img/logo' alt="logo" /-->
        <h1>attractions<i class="fas fa-circle"></i><span>Admin panel</span></h1>
    </div>
    <?php
       if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==TRUE){
        if($_SESSION['type_compte']=="admin"){ ?>
    <div class="dropdown">
        <button class='account'>
            <!--img src="/img/blank-profile-picture.jpg"/-->
            <img class='avatar' src='https://ui-avatars.com/api/?name=<?php echo $_SESSION['username']; ?>&rounded=true&bold=true'/>
        </button>
        <div class="dropdown-content">
            <a href='/logout'>Se déconnecter <i class='fa fa-sign-out-alt'></i></a>
        </div>
    </div>
    <?php
        }
    }
    ?>
</nav>
</div>