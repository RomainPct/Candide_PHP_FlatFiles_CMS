<?php
$c = new CandidePageAdministrator($_GET["name"]);
?>
<h1><?php $texts->echo("page") ?> "<?php $c->echoFormattedInstanceName() ?>"</h1>
<form id="editPageForm" method="post" action="php/actions/savePage.php" enctype="multipart/form-data">
    <input type="hidden" name="pageName" data-url="<?php echo $_GET['name'] ?>" value="<?php echo $_GET['name'] ?>">
    <?php $c->getFields() ?>
    <div class="submitContainer">
        <input type="submit" value="<?php $texts->echo("save") ?>">
        <span class="success"><?php $texts->echo("saved") ?></span>
    </div>
</form>
<?php $c->getCustomCSSAndJS() ?>