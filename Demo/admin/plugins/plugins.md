# Insert a plugin
To insert a plugin in Candide, just put the plugin folder in the folder called "plugins".

# Develop a plugin
## Visual plugin with an interface
Pass is_visual_interface to true in config.json
‘‘‘json
{
    "name":"Plugin_name",
    "version":"1.0.0",
    "is_visual_interface":true,
    "is_candide_class_extension":false,
    "is_backend_extension":false
}
‘‘‘
Add an index.php file in your plugin folder.
Now your plugin appear in the Candide sidebar and redirect to your Plugin index where you can do what you want

## Backend extension plugin
‘‘‘json
{
    "name":"Plugin_name",
    "version":"1.0.0",
    "is_visual_interface":false,
    "is_candide_class_extension":true,
    "is_backend_extension":false
}
‘‘‘
Add a eventHandler.php file in your plugin folder.
Then handle events that interest you.
‘‘‘php
<?php

switch ($_GET["event"]) {
    case Notification::NEW_PICTURE_SAVED:
        // Do something
        break;
    default:
        trigger_error('Event "'.$_GET["event"].'" is not supported by sample_backend_plugin');
        break;
}

‘‘‘