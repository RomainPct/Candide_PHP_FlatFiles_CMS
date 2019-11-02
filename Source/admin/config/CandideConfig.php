<?php

const DEV_MODE = true;
const PROJECT_NAME = "YOUR_PROJECT_NAME_THERE";
const LOCALE = "en-en";

// Default password is "admin"
const ADMINISTRATORS = [
    ["admin","8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918"]
];

// Relative urls from root directory
const CANDIDE_FILES_FOLDERS = [
    "/",
    "/pages/"
];

const CANDIDE_IS_CONFIGURED = true;

// remove the choice to move custom css
const CUSTOM_CSS = "/admin/config/custom.css";

// To replace with HTMl config
const WELCOME_TITLE = "Welcome on the Candide administration platform";
const WELCOME_PARAGRAPH = "You can edit your website content thank's to the left panel.";