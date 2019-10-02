<?php
session_start();
const ADMIN_AUTOLOAD_DIRECTORIES = ["php/administration/","php/collectionAdministration/"];
require 'Candide.php';
Authentication::check();