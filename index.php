<?php

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Max-Age: 86400');
    exit(0);
}

session_start();

require "config/error-handler.php";
require "core/Autoloader.php";

Setup::initializeCookies();

require "config/configuration.php";

require "routers/index.php";
