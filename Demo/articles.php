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
        <img src="<?php $item->image("miniature",[400,100]) ?>" alt="">
        <h1><?php $item->text("titre") ?></h1>
        <p><?php $item->number("partages") ?></p>
        <a href="article.php?id=<?php $item->id() ?>">En savoir plus</a>
    </article>
    <?php
}
?>

</body>
</html>