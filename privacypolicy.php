<?php
require_once $_SERVER['DOCUMENT_ROOT']."/config.php";
ob_start();
session_start();
include SITE_ROOT.'/db_connect.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<title>Politique de confidentialité</title>
<meta name="description" content="Notre Politique de confidentialité">
<?php include 'global_head_tags.php'; ?>
</head>
<body>
<?php $page_name = "politique_de_confidentialite"; include 'nav.php'; ?>
<div class="wrapper">
<div class='aboutus'>
    <article>
    <section>
        <h1>Politique de confidentialité</h1>
        <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin pulvinar, libero quis faucibus porta, dolor mi scelerisque tellus, et volutpat augue sapien sit amet diam. Fusce ac orci venenatis, fringilla tellus in, viverra elit. Aliquam egestas, felis non aliquet ornare, arcu sem ultrices elit, non dignissim nisi purus non tellus. Morbi iaculis sit amet massa ultricies semper. In hac habitasse platea dictumst. Nunc cursus nisl a justo pretium, at commodo nisl tincidunt. Integer aliquet quam vel augue hendrerit, non interdum nisi rhoncus. Phasellus cursus est vitae magna finibus, ut aliquam massa efficitur. Ut ut leo quis dui maximus lobortis non ac nibh. In nisi enim, porttitor vitae placerat at, porta nec ipsum. Nulla ac urna sed ligula tincidunt interdum.

Proin ultrices sem a sem gravida, ac scelerisque elit gravida. Ut semper imperdiet hendrerit.
Nullam tincidunt imperdiet leo a rutrum. Proin suscipit felis eu nibh rutrum vulputate. In quis sodales dui. Ut sem lorem, ultricies eu malesuada nec, convallis eu mauris. Morbi bibendum, metus eget hendrerit dapibus, dolor sapien maximus dolor, non porttitor nibh libero ut justo. Curabitur ac nulla sit amet lacus lacinia volutpat. Cras rhoncus arcu et tellus malesuada congue. Sed ut nisi ut elit pulvinar tempus vel eget eros.

Integer vel neque odio. Quisque aliquam nec purus eu dapibus. Cras placerat pretium lorem sed commodo. Quisque vitae quam arcu. Aenean placerat dolor vel vehicula aliquet. Donec rhoncus mi risus, in aliquet nibh tempor vel. Nullam nec ultricies leo, at gravida eros. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Praesent scelerisque magna placerat, lobortis nisl sit amet, vehicula quam. Nulla tempor, felis vel rhoncus consequat, leo lacus bibendum elit, sed congue massa mauris eget nibh. Duis eu diam ac erat tincidunt aliquet eget vel ipsum. Phasellus aliquet elit sit amet magna dignissim, eget consequat nisl tincidunt. Ut et orci ac mauris lacinia tristique. Praesent at pellentesque libero.

Integer dapibus maximus urna, sit amet accumsan ex tempor et. Nulla augue mauris, tincidunt id facilisis et, elementum vitae nisl. Nunc sodales id nibh consectetur lobortis. Fusce consectetur lorem nibh, vel ornare eros tempus at. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Cras vehicula posuere vulputate. In id commodo dolor. Cras in volutpat purus.

Nam et pellentesque orci, et porttitor nisi. Nullam non justo non leo dictum aliquam. Praesent nec erat erat. Phasellus ac finibus quam. Vestibulum at felis nulla. Sed pretium faucibus dignissim. Nullam porttitor ex in scelerisque varius. Sed congue ipsum vitae augue porttitor placerat.
</p>
    </section>
    <section>
        <h2>Lorem Ipsum</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin pulvinar, libero quis faucibus porta, dolor mi scelerisque tellus, et volutpat augue sapien sit amet diam. Fusce ac orci venenatis, fringilla tellus in, viverra elit. Aliquam egestas, felis non aliquet ornare, arcu sem ultrices elit, non dignissim nisi purus non tellus. Morbi iaculis sit amet massa ultricies semper. In hac habitasse platea dictumst. Nunc cursus nisl a justo pretium, at commodo nisl tincidunt. Integer aliquet quam vel augue hendrerit, non interdum nisi rhoncus. Phasellus cursus est vitae magna finibus, ut aliquam massa efficitur. Ut ut leo quis dui maximus lobortis non ac nibh. In nisi enim, porttitor vitae placerat at, porta nec ipsum. Nulla ac urna sed ligula tincidunt interdum.
        </p>
<ul>
    <li>Proin ultrices sem a sem gravida, ac scelerisque elit gravida. Ut semper imperdiet hendrerit</li>
    <li>Nullam tincidunt imperdiet leo a rutrum. Proin suscipit felis eu nibh rutrum vulputate</li>
    <li> In quis sodales dui. Ut sem lorem, ultricies eu malesuada nec, convallis eu mauris. Morbi bibendum</li>
    <li>metus eget hendrerit dapibus, dolor sapien maximus dolor, non porttitor nibh libero ut justo.</li>
</ul>
<p> Curabitur ac nulla sit amet lacus lacinia volutpat. Cras rhoncus arcu et tellus malesuada congue. Sed ut nisi ut elit pulvinar tempus vel eget eros.
</p>

    </section>
 </article>
</div>
<?php include 'footer.php'; ?>
</div>
</body>
</html>