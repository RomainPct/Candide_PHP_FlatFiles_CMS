<?php
$c = new CandideCollectionAdministrator($_GET["page"]);
?>
<h1><?php $texts->echo("collection") ?> "<?php $c->getPageName() ?>"</h1>
<div class="submitContainer clickable">
    <a href="editCollectionItem?page=<?php echo $_GET["page"] ?>&id=newItem"><?php $texts->echo("new_item") ?></a>
</div>
<?php
foreach ($c->items() as $item) {
    ?>
    <div class="collectionItemBox">
        <h2><?php $item->getElementTitle() ?></h2>
        <a class="button editButton" href="editCollectionItem?id=<?php echo $item->id() ?>&page=<?php echo $_GET["page"] ?>"></a>
        <a class="button deleteButton" href="php/actions/deleteCollectionItem.php?candide_page_name=<?php echo $_GET["page"] ?>&candide_index=<?php echo $item->id() ?>"></a>
    </div>
    <?php
}
?>