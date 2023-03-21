<div class='sidebar'>
    <div class='links'>
        <a <?php if($page_name=='dashboard') echo "class='active'"; ?>  href='/admin/'><i class='fa fa-calendar-day'></i>&nbsp;&nbsp;Attractions</a>
        <a <?php if($page_name=='utilisateurs') echo "class='active'"; ?>  href='utilisateurs'><i class='fa fa-users'></i>&nbsp;&nbsp;Utilisateurs</a>
        <a <?php if($page_name=='reservations') echo "class='active'"; ?>  href='reservations'><i class='fa fa-calendar-check'></i>&nbsp;&nbsp;Réservations</a>
        <a <?php if($page_name=='membres') echo "class='active'"; ?> href='membres'><i class='fa fa-user-shield'></i>&nbsp;&nbsp;Membres</a>
    </div>
</div>