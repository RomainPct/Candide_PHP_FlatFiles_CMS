<?php
if (DEV_MODE) {
?>
    <div class="submitContainer clickable">
        <a id="updateAdminPlatform" href="php/actions/updateAdminPlatform.php"><?php $texts->echo("updateAdminPlatform") ?></a>
    </div>
<?php
}
include 'config/customHome.php';
?>