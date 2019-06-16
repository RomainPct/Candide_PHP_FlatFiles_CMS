<?php
include_once '../Candide.php';
include_once 'CandideAdmin.php';
$c = new CandideIndex();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Candide</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/styles/main.min.css">
    <link rel="stylesheet" type="text/css" href="src/trix-master/dist/trix.css">
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
    <a id="logout" href="login.php?logout=yes">Déconnexion</a>
</nav>

<div id="content">
    <?php include 'pages/home.php' ?>
</div>

<script src="assets/scripts/main.min.js"></script>
</body>
</html>