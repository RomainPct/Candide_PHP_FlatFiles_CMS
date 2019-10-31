<?php

switch ($_GET["event"]) {
    case Notification::NEW_PICTURE_SAVED:
        // Do something
        echo "\nEventHandler of sample_backend_plugin works !\nInfos :\n";
        var_dump($_GET["informations"]);
        break;
    default:
        trigger_error('Event "'.$_GET["event"].'" is not supported by sample_backend_plugin');
        break;
}

