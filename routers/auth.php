<?php

$router->get("/auth/login", ["AuthGetController", "Login"]);
$router->get("/auth/register", ["AuthGetController", "Register"]);
$router->get("/auth/profile", ["AuthGetController", "Profile"]);

$router->post("/auth/register", ["AuthPostController", "Register"]);
$router->post("/auth/login", ["AuthPostController", "Login"]);

$router->get("/auth/logout", ["AuthDeleteController", "Logout"]);