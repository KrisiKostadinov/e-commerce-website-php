<?php

class AuthGetController
{
    public static function Register(): void
    {
        AuthService::isAuth() ? Setup::redirect("/") : null;
        Setup::View("auth/register");
    }

    public static function Login(): void
    {
        AuthService::isAuth() ? Setup::redirect("/") : null;
        Setup::View("auth/login");
    }
}