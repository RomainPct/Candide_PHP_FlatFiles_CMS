<?php
include_once '../Candide.php';
include_once 'CandideAdmin.php';
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
<?php include("pages/components/sidebar.php") ?>

<div id="content">
    <?php include 'pages/rooter.php' ?>
</div>

<script src="assets/scripts/main.min.js"></script>
</body>
</html>