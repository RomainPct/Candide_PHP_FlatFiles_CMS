<?php
$c = new CandideCollectionAdministrator($_GET["name"]);
?>
<h1><?php $texts->echo("collection") ?> "<?php $c->echoFormattedInstanceName() ?>"</h1>
<div class="submitContainer clickable">
    <a href="editCollectionItem?collection_name=<?php echo $_GET["name"] ?>&id=newItem"><?php $texts->echo("new_item") ?></a>
</div>
<?php
foreach ($c->items() as $item) {
    ?>
    <div class="collectionItemBox">
        <h2><?php $item->getElementTitle() ?></h2>
        <a class="button editButton" href="editCollectionItem?id=<?php echo $item->id() ?>&collection_name=<?php echo $_GET["name"] ?>"></a>
        <a class="button deleteButton" href="php/actions/deleteCollectionItem.php?candide_instance_name=<?php echo $_GET["name"] ?>&candide_index=<?php echo $item->id() ?>"></a>
    </div>
    <?php
}
?>