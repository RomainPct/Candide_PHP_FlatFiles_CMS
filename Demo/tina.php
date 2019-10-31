<?php
require 'admin/Candide.php';
$c = new CandidePage("tina_la_best");
?>
<html>
<head>
    <title><?php $c->text("page_title") ?></title>
</head>
<body>
<p><?php $c->text("presentation_tina") ?></p>
<img src="<?php $c->image('profil',[400,400]) ?>" alt="Tina El Hawa">
<p><?php $c->text("presentation") ?></p>
<p><?php $c->text("conclusion") ?></p>
<img src="<?php $c->image('end_picture',[600,300]) ?>" alt="Tina El Hawa end picture">
</body>
</html>