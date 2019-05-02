<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once '../Candide.php';
$c = new CandideIndex();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Candide</title>

    <link rel="stylesheet" href="assets/styles/main.min.css">
</head>
<body>
<nav>
    <div id="header">
        <a class="logo" href="/admin" title="Accueil">Candide</a>
    </div>
    <ul id="navLinks">
        <li class="sectionTitle">Pages</li>
        <?php
        for ($i = 0; $i < $c->countPages(); $i++){
            echo '<li><a data-type="page" href="#'.$c->getPageName($i).'">'.$c->getPage($i).'</a></li>';
        }
        ?>
        <li class="sectionTitle">Collections</li>
        <?php
        for ($i = 0; $i < $c->countCollections(); $i++){
            echo '<li><a data-type="collection" href="#'.$c->getCollectionName($i).'">'.$c->getCollection($i).'</a></li>';
        }
        ?>
    </ul>
    <a id="siteAccessButton" href="../" >Accéder au site</a>
</nav>

<div id="content">
    <?php include 'pages/home.php' ?>
</div>

<script src="src/js/main.js"></script>
</body>
</html>