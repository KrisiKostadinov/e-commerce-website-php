<?php

class AuthDeleteController
{
    public static function Logout(): void
    {
        if ($_GET['_method'] === 'DELETE') {
            AuthService::logout();
            Setup::redirect("/");
        }
    }
}