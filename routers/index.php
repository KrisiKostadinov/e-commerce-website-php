<?php

$router = new Router();

$uri = $_SERVER["REQUEST_URI"];
$method = $_SERVER["REQUEST_METHOD"];

$router->get("/", ["IndexGetController", "Home"]);
$router->get("/about", ["IndexGetController", "About"]);
$router->get("/contacts", ["IndexGetController", "Contacts"]);
$router->get("/privacy-policy", ["IndexGetController", "Policy"]);

$router->post("/contacts", ["IndexPostController", "Contacts"]);

require "auth.php";
require "products.php";
require "categories.php";

$router->route($uri, $method);
