<?php

const PROJECT_NAME = "candide_project";

// LCID String https://www.science.co.il/language/Locale-codes.php
const LOCALE = "en-en";

const WELCOME_TITLE = "Welcome on the Candide administration platform";
const WELCOME_PARAGRAPH = "You can edit your website content thank's to the left panel.";

// Default password is "administrator"
const ADMINISTRATORS = [
    ["admin","4194D1706ED1F408D5E02D672777019F4D5385C766A8C6CA8ACBA3167D36A7B9"]
];

// Relative urls from root directory
const CANDIDE_FILES_FOLDERS = [
    "/",
    "/pages/"
];

const DEV_MODE = true;
const CANDIDE_IS_CONFIGURED = true;
const CUSTOM_CSS = "/admin/config/custom.css";