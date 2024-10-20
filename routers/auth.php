<?php

$router->get("/auth/login", ["AuthGetController", "Login"]);
$router->get("/auth/register", ["AuthGetController", "Register"]);

$router->post("/auth/register", ["AuthPostController", "Register"]);