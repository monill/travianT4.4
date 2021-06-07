<?php

ini_set("display_errors", 0);
error_reporting(E_ALL);
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'Protection.php');
include_once("Database/connection.php");
include_once("config.php");
if (!isset($security_system) && $security_system != 1) die("<b>Error:</b> Security Setting Must Be Enabled To Run Script!...");
include_once("Database/db_MYSQL.php");
