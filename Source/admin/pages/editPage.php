<?php
$c = new CandidePageAdministrator($_GET["page"]);
?>
<h1><?php $texts->echo("page") ?> "<?php $c->getPageName() ?>"</h1>
<form id="editPageForm" method="post" action="php/actions/savePage.php" enctype="multipart/form-data">
    <input type="hidden" name="pageName" id="pageName" data-url="<?php echo $_GET['page'] ?>" value="<?php echo $_GET['page'] ?>">
    <?php $c->getFields() ?>
    <div class="submitContainer">
        <input type="submit" value="<?php $texts->echo("save") ?>">
        <span class="success"><?php $texts->echo("saved") ?></span>
    </div>
</form>
<?php $c->getCustomCSSAndJS() ?>