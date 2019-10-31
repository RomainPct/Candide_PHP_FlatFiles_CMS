<?php
include '../admin/Candide.php';
$c = new CandideCollection("episodes_podcast",["sample_candide_class_plugin"]);
foreach ($c->items() as $item) {
    ?>
    <article>
        <h1><?php $item->text("nom") ?></h1>
        <strong><?php $item->number("vues") ?></strong>
        <img src="<?php $item->image("bandeau",[800,200]) ?>" alt="Alt">
        <?php $item->youtube_video("video") ?>
    </article>
    <?php
}
?>

