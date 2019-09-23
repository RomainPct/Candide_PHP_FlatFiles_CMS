<?php
session_start();
const ADMIN_AUTOLOAD_DIRECTORIES = ["php/administration/"];
require 'Candide.php';
Authentication::check();