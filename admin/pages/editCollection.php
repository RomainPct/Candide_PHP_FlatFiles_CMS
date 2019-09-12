<?php
$c = new CandideCollectionAdministrator($_GET["page"]);
?>
<h1>Collection "<?php $c->getPageName() ?>"</h1>
<div class="submitContainer clickable">
    <a href="editCollectionItem?page=<?php echo $_GET["page"] ?>&id=newItem">Nouvel élément</a>
</div>
<?php
foreach ($c->avalaibleItemIds() as $itemId){
    ?>
    <div class="collectionItemBox">
        <h2><?php $c->getElementTitle($itemId) ?></h2>
        <a class="button editButton" href="editCollectionItem?id=<?php echo $itemId ?>&page=<?php echo $_GET["page"] ?>"></a>
        <a class="button deleteButton" href="php/actions/deleteCollectionItem.php?candide_page_name=<?php echo $_GET["page"] ?>&candide_index=<?php echo $itemId ?>"></a>
    </div>
    <?php
}
?>