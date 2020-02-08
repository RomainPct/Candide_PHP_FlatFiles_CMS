<?php
require 'admin/Candide.php';
$c = new CandidePage("about_us");
?>
<html>
<head>
    <title><?php $c->text("page_title") ?></title>
</head>
<body>
<p><?php $c->text("presentation") ?></p>
<img src="<?php $c->image('banner',[1080,270]) ?>" alt="Picture of the creator">
<p><?php $c->text("conclusion",true) ?></p>
<img src="<?php $c->image('end_picture',[600,300]) ?>" alt="Tina El Hawa end picture">
</body>
</html>