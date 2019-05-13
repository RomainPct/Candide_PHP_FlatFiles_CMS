<?php
include_once '../../Candide.php';
include_once '../CandideAdmin.php';
$c = new CandideCollectionAdministrator($_GET["page"]);
?>
<h1>Collection "<?php $c->getPageName() ?>"</h1>
<div class="submitContainer clickable">
    <a id="newElement" href="#<?php echo $_GET["page"] ?>">Nouvel élément</a>
</div>
<?php
foreach ($c->avalaibleItemIds() as $itemId){
    ?>
    <div class="collectionItemBox">
        <h2><?php $c->getElementTitle($itemId) ?></h2>
        <a class="button editButton" href="#<?php echo $itemId ?>"></a>
        <a class="button deleteButton" href="actions/deleteCollectionItem.php?candide_page_name=<?php echo $_GET["page"] ?>&candide_index=<?php echo $itemId ?>"></a>
    </div>
    <?php
}
?>