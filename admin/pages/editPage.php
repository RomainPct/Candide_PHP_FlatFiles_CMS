<?php
include_once '../../Candide.php';
include_once '../CandideAdmin.php';
$c = new CandidePageAdministrator($_GET["page"]);
?>
<h1>Page "<?php $c->getPageName() ?>"</h1>
<form id="editPageForm" method="post" action="actions/savePage.php" enctype="multipart/form-data">
    <input type="hidden" name="pageName" id="pageName" data-url="<?php echo $_GET['page'] ?>" value="<?php echo $_GET['page'] ?>">
    <?php $c->getFields() ?>
    <div class="submitContainer">
        <input type="submit" value="Enregistrer">
    </div>
</form>