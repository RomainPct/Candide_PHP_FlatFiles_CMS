<?php
$c = new CandideCollectionItemAdministrator($_GET["page"],$_GET["id"]);
?>
<h1><a id="backButton" href="editCollection?page=<?php echo $_GET['page'] ?>"></a><?php $c->getTitle($texts) ?></h1>
<form id="editCollectionItemForm" data-page="<?php echo $_GET["page"] ?>" data-id="<?php echo $_GET["id"] ?>" method="post" action="php/actions/saveCollectionItem.php" enctype="multipart/form-data">
    <input type="hidden" name="candide_page_name" id="pageName" data-url="<?php echo $_GET['page']."/".$_GET["id"] ?>" value="<?php echo $_GET['page'] ?>">
    <input type="hidden" name="candide_index" value="<?php echo $_GET['id'] ?>">
    <?php $c->getFields() ?>
    <div class="submitContainer">
        <input type="submit" value="<?php $c->getCallToActionText($texts) ?>">
        <span class="success"><?php $texts->echo("saved") ?></span>
    </div>
</form>