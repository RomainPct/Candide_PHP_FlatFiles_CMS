<?php
include_once $_SERVER["DOCUMENT_ROOT"].'/admin/config/CandideConfig.php';
if (devMode) {
?>
    <div class="submitContainer clickable">
        <a id="updateAdminPlatform" href="updateAdminPlatform.php">Mettre à jour la plateforme administrateur</a>
    </div>
<?php
}
?>
<h1><?php echo welcomeTitle ?></h1>
<p><?php echo welcomeParagraph ?></p>