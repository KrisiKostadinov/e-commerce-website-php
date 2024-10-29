<?php

class AuthGetController
{
    public static function Register(): void
    {
        $generator = new MetaTagsGenerator();
        $metaTags = $generator->generate([
            "title" => "Създаване на нов профил",
        ]);

        AuthService::isAuth() ? Setup::redirect("/") : null;
        Setup::View("auth/register", [
            "metaTags" => $metaTags,
        ]);
    }

    public static function Login(): void
    {
        $generator = new MetaTagsGenerator();
        $metaTags = $generator->generate([
            "title" => "Вход в системата",
        ]);

        AuthService::isAuth() ? Setup::redirect("/") : null;
        Setup::View("auth/login", [
            "metaTags" => $metaTags,
        ]);
    }
}