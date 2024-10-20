<?php

class AuthGetController
{
    public static function Register(): void
    {
        Setup::View("auth/register");
    }

    public static function Login(): void
    {
        Setup::View("auth/login");
    }
}