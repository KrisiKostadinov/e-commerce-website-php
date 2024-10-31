<?php

class AuthGetController
{
    private static function generateMetaTags(array $metaTags = []): string
    {
        $generator = new MetaTagsGenerator();
        $metaTags = $generator->generate($metaTags);
        return $metaTags;
    }

    public static function Register(): void
    {
        $metaTags = self::generateMetaTags([
            "title" => LANGUAGE["create_new_account"]
        ]);

        $secureToken = Generations::generateToken(Generations::generateFourDigitCode());
        $_SESSION["secure_token"] = $secureToken;

        AuthService::isAuth() ? Setup::redirect("/") : null;
        Setup::View("auth/register", [
            "metaTags" => $metaTags,
        ]);
    }

    public static function Login(): void
    {
        $metaTags = self::generateMetaTags([
            "title" => LANGUAGE["login_to_account"]
        ]);

        $secureToken = Generations::generateToken(Generations::generateFourDigitCode());
        $_SESSION["secure_token"] = $secureToken;

        AuthService::isAuth() ? Setup::redirect("/") : null;
        Setup::View("auth/login", [
            "metaTags" => $metaTags,
        ]);
    }

    public static function ForgotPassword(): void
    {
        $metaTags = self::generateMetaTags([
            "title" => LANGUAGE["forgot_password"]
        ]);

        $secureToken = Generations::generateToken(Generations::generateFourDigitCode());
        $_SESSION["secure_token"] = $secureToken;

        AuthService::isAuth() ? Setup::redirect("/") : null;
        Setup::View("auth/forgot-password", [
            "metaTags" => $metaTags,
        ]);
    }
}