<?php
require 'admin/Candide.php';
$c[0] = new CandidePage("home");
$c[1] = new CandidePage("about_us");
?>
<html>

<head>
    <title><?= $c[0]->text("page_title") ?></title>
</head>

<body>
    <h2><?= $c[1]->text('presentation') ?></h2>
    <p><?= $c[0]->text("welcome") ?></p>
    <img src="<?= $c[0]->image("profil",[1000,1000]) ?>" alt="Romain Penchenat">
    <p><?= $c[0]->text("presentation",true) ?></p>
    <p><?= $c[0]->text("conclusion") ?></p>
</body>

</html>