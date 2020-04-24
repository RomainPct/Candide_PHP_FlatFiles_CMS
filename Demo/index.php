<?php
require 'admin/Candide.php';
$c[0] = new CandidePage("home");
$c[1] = new CandidePage("about_us");
?>
<html>

<head>
    <title><?php $c[0]->text("page_title") ?></title>
</head>

<body>
    <h2><?php $c[1]->text('presentation') ?></h2>
    <p><?php $c[0]->text("welcome") ?></p>
    <img src="<?php $c[0]->image("profil",[1000,1000]) ?>" alt="Romain Penchenat">
    <p><?php $c[0]->text("presentation",true) ?></p>
    <p><?php $c[0]->text("conclusion") ?></p>
</body>

</html>