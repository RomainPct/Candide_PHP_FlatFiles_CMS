<?php
require 'admin/Candide.php';
$c = new CandidePage("index");
?>
<html>
<head>
<title><?php $c->text("page_title") ?></title>
</head>
<body>
<p><?php $c->text("welcome") ?></p>
<img src="<?php $c->image("profil",[1000,1000]) ?>" alt="Romain Penchenat">
<p><?php $c->text("presentation",true) ?></p>
<p><?php $c->text("conclusion") ?></p>
</body>
</html>