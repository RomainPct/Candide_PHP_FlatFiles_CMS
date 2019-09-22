<?php

trait BackendPluginNotifier {

    protected $backendPlugins;

    protected function sendNotification() {

    }

}

class Notification extends SplEnum {

    const NEW_PICTURE_SAVED = "newPictureSaved";

}