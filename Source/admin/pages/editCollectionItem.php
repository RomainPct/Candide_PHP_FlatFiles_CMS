<?php
$c = new CandideCollectionItemAdministrator($_GET["collection_name"],$_GET["id"]);
?>
<h1><a id="backButton" href="editCollection?name=<?php echo $_GET['collection_name'] ?>"></a><?php $c->getTitle($texts) ?></h1>
<form id="editCollectionItemForm" data-collection-name="<?php echo $_GET["collection_name"] ?>" data-id="<?php echo $_GET["id"] ?>" method="post" action="php/actions/saveCollectionItem.php" enctype="multipart/form-data">
    <input type="hidden" name="candide_instance_name" data-url="<?php echo $_GET['collection_name']."/".$_GET["id"] ?>" value="<?php echo $_GET['collection_name'] ?>">
    <input type="hidden" name="candide_index" value="<?php echo $_GET['id'] ?>">
    <?php $c->getFields() ?>
    <div class="submitContainer">
        <input type="submit" value="<?php $c->getCallToActionText($texts) ?>">
        <span class="success"><?php $texts->echo("saved") ?></span>
    </div>
</form>
<?php $c->getCustomCSSAndJS() ?>