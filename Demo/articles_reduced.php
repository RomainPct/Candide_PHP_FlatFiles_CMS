<?php
require 'admin/Candide.php';
$c = new CandideCollection("articles");
?>
<html>
<head>
<title>Collection d'article</title>
</head>
<body>
<?php
foreach($c->items() as $item) {
    ?>
    <article>
        <h1><?php $item->text("titre") ?></h1>
        <a href="article.php?id=<?php $item->id() ?>">En savoir plus</a>
    </article>
    <?php
}
?>

</body>
</html>