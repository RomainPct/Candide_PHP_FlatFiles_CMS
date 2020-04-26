<?php
require 'admin/Candide.php';
$c = new CandidePage("about_us");
?>
<html>
<head>
    <title><?= $c->text("page_title") ?></title>
</head>
<body>
<p><?= $c->text("presentation") ?></p>
<img src="<?= $c->image('banner',[1080,270]) ?>" alt="Picture of the creator">
<p><?= $c->text("conclusion",true) ?></p>
<img src="<?= $c->image('end_picture',[600,300]) ?>" alt="Tina El Hawa end picture">
</body>
</html>