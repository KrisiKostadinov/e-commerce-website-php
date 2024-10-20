<?php

$router = new Router();

$uri = $_SERVER["REQUEST_URI"];
$method = $_SERVER["REQUEST_METHOD"];

$router->get("/", ["IndexGetController", "Home"]);
$router->get("/contacts", ["IndexGetController", "Contacts"]);

$router->post("/contacts", ["IndexPostController", "Contacts"]);

require "auth.php";

$router->route($uri, $method);
