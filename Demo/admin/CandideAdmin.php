<?php
session_start();
const ADMIN_AUTOLOAD_DIRECTORIES = ["php/administration/","php/collectionAdministration/"];
const CUSTOM_CSS = "/admin/config/custom.css";
require 'Candide.php';
Authentication::check();