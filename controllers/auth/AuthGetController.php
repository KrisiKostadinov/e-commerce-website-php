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

    public static function PasswordRecovery(): void
    {
        $userResult = AuthService::get("password_reset_token", $_GET["token"] ?? "");
        if (!$userResult["success"]) {
            Setup::setSession("error_message", LANGUAGE["invalid_link"]);
            Setup::redirect("/auth/login");
        }

        $user = $userResult["data"];

        if ($user["token_expiry"] < time()) {
            Setup::setSession("error_message", LANGUAGE["invalid_link"]);
            Setup::redirect("/auth/login");
        }

        $metaTags = self::generateMetaTags([
            "title" => LANGUAGE["password_recovery"]
        ]);

        $secureToken = Generations::generateToken(Generations::generateFourDigitCode());
        $_SESSION["secure_token"] = $secureToken;

        AuthService::isAuth() ? Setup::redirect("/") : null;
        Setup::View("auth/password-recovery", [
            "metaTags" => $metaTags,
        ]);
    }

    public static function VerifyEmail(): void
    {
        global $db;
        $db->beginTransaction();

        $userResult = AuthService::get("email_confirmation_token", $_GET["token"] ?? "");
        if (!$userResult["success"]) {
            Setup::redirect("/");
        }

        $user = $userResult["data"];
        
        $db->update("users", [
            "email_confirmation_token" => null,
            "is_email_confirmed" => 1,
        ], [
            "id" => $user["id"]
        ]);
        
        AuthService::sendEmailConfirmationSuccessEmail($user["email"]);

        $db->commit();
        
        Setup::setSession("success_message", LANGUAGE["email_is_confirmed"]);
        Setup::redirect("/auth/login");
    }
}
