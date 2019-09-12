<?php
$c = new CandideIndex();
?>
<nav>
    <div id="header">
        <a class="logo" href="/admin" title="Accueil">Candide</a>
    </div>
    <ul id="navLinks">
        <li class="sectionTitle">Pages</li>
        <?php
        for ($i = 0; $i < $c->countPages(); $i++){
            echo '<li><a href="editPage?page='.$c->getPageName($i).'">'.$c->getPage($i).'</a></li>';
        }
        ?>
        <li class="sectionTitle">Collections</li>
        <?php
        for ($i = 0; $i < $c->countCollections(); $i++){
            echo '<li><a href="editCollection?page='.$c->getCollectionName($i).'">'.$c->getCollection($i).'</a></li>';
        }
        ?>
    </ul>
    <a id="siteAccessButton" href="../" >Accéder au site</a>
    <a id="logout" href="login.php?logout=yes">Déconnexion</a>
</nav>