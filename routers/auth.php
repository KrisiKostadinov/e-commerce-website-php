<?php

$router->get("/auth/login", ["AuthGetController", "Login"]);
$router->get("/auth/register", ["AuthGetController", "Register"]);
$router->get("/auth/profile", ["AuthGetController", "Profile"]);
$router->get("/auth/forgot-password", ["AuthGetController", "ForgotPassword"]);
$router->get("/auth/password-recovery", ["AuthGetController", "PasswordRecovery"]);
$router->get("/auth/verify-email", ["AuthGetController", "VerifyEmail"]);

$router->post("/auth/register", ["AuthPostController", "Register"]);
$router->post("/auth/login", ["AuthPostController", "Login"]);
$router->post("/auth/forgot-password", ["AuthPostController", "ForgotPassword"]);
$router->post("/auth/password-recovery", ["AuthPostController", "PasswordRecovery"]);

$router->get("/auth/logout", ["AuthDeleteController", "Logout"]);
