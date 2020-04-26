<?php
include '../admin/Candide.php';
$c = new CandideCollection("episodes_podcast",["sample_candide_class_plugin"]);
foreach ($c->items() as $item) {
    ?>
    <article>
        <h1><?= $item->text("nom") ?></h1>
        <strong><?= $item->number("vues") ?></strong>
        <img src="<?= $item->image("bandeau",[800,200]) ?>" alt="Alt">
        <?= $item->youtube_video("video") ?>
    </article>
    <?php
}
?>

