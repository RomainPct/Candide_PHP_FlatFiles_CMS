<?php
$c = new CandideIndex();
$texts = new AdminTextsManager("sidebar");
?>
<nav>
    <div id="header">
        <a class="logo" href="../admin" title="Accueil">Candide</a>
        <a id="js_hamburgerButton" class="hamburgerButton" href="#" ></a>
    </div>
    <ul class="navSection" id="navLinks">
            <li class="sectionTitle"><?php $texts->echo("pages") ?></li>
            <?php
            for ($i = 0; $i < $c->countPages(); $i++){
                echo '<li><a href="editPage?page='.$c->getPageName($i).'">'.$c->getPage($i).'</a></li>';
            }
            ?>
            <li class="sectionTitle"><?php $texts->echo("collections") ?></li>
            <?php
            for ($i = 0; $i < $c->countCollections(); $i++){
                echo '<li><a href="editCollection?page='.$c->getCollectionName($i).'">'.$c->getCollection($i).'</a></li>';
            }
            ?>
            <?php
                if ($c->countPlugins() > 0) {
                    echo '<li class="sectionTitle">'.$texts->get("plugins").'</li>';
                }
            ?>
            <?php
            for ($i = 0; $i < $c->countPlugins(); $i++){
                echo '<li><a href="../admin/?plugin='.$c->getPluginName($i).'">'.$c->getPluginNameFormatted($i).'</a></li>';
            }
            ?>
        </ul>
    <div class="navSection">
        <a id="siteAccessButton" href="../" ><?php $texts->echo("open_website") ?></a>
        <a id="logout" href="login.php?logout=yes"><?php $texts->echo("logout") ?></a>
    </div>
</nav>