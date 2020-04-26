<?php
require 'admin/Candide.php';
$c = new CandideCollectionItem("articles",$_GET["id"],["sample_candide_class_plugin"]);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Article précis</title>
</head>
<body>

<h1><?= $c->text("titre") ?></h1>
<p><?= $c->text("content",true) ?></p>

</body>
</html>
