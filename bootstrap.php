<?php

require_once "vendor/autoload.php";
require_once "helper.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();