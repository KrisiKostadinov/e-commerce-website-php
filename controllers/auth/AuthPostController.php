<?php

class AuthPostController
{
    private static array $registerFields = ["email", "password", "cpassword"];
    private static array $loginFields = ["email", "password"];
    private static array $forgotPasswordFields = ["email"];
    private static array $recoveryPasswordFields = ["password", "cpassword", "token"];

    private static function render($errorMessage, $callback)
    {
        Setup::setSession("error_message", $errorMessage);
        Setup::setSession("post", $_POST);
        $callback();
    }

    private static function checkAccess($function): void
    {
        if (
            empty($_POST["secure_token"])
            || empty($_SESSION["secure_token"])
            || $_POST["secure_token"] !== $_SESSION["secure_token"]
        ) {
            self::render(LANGUAGE["access_denied"], $function);
        }

        unset($_SESSION["secure_token"]);
    }

    public static function Register(): void
    {
        self::checkAccess([AuthGetController::class, "Register"]);

        AuthService::isAuth() ? Setup::redirect("/") : null;

        $preparedData = [];

        foreach (self::$registerFields as $field) {
            $preparedData[$field] = $_POST[$field] ?? null;
        }

        $userResult = AuthService::register(...$preparedData);

        if (!$userResult["success"]) {
            self::render($userResult["error"], [AuthGetController::class, "Register"]);
        }

        Setup::redirect("/auth/login", 200);
    }

    public static function Login(): void
    {
        self::checkAccess([AuthGetController::class, "Login"]);

        AuthService::isAuth() ? Setup::redirect("/") : null;

        $preparedData = [];

        foreach (self::$loginFields as $field) {
            $preparedData[$field] = $_POST[$field] ?? null;
        }

        $result = AuthService::login(...$preparedData);

        if ($result["success"] === false) {
            Setup::setSession("error_message", $result["error"]);
            Setup::setSession("post", $_POST);
            AuthGetController::Login();
        }

        Setup::redirect("/", 200);
    }

    public static function ForgotPassword(): void
    {
        self::checkAccess([AuthGetController::class, "ForgotPassword"]);

        AuthService::isAuth() ? Setup::redirect("/") : null;

        $preparedData = [];

        foreach (self::$forgotPasswordFields as $field) {
            $preparedData[$field] = $_POST[$field] ?? null;
        }

        $result = AuthService::forgotPassword(...$preparedData);

        if ($result["success"] === false) {
            Setup::setSession("error_message", $result["error"]);
            Setup::setSession("post", $_POST);
            AuthGetController::ForgotPassword();
        }

        $_SESSION["success_message"] = $result["message"];
        Setup::redirect("/auth/forgot-password", 200);
    }

    public static function PasswordRecovery(): void
    {
        self::checkAccess([AuthGetController::class, "PasswordRecovery"]);

        AuthService::isAuth() ? Setup::redirect("/") : null;

        $preparedData = [];

        foreach (self::$recoveryPasswordFields as $field) {
            $preparedData[$field] = $_POST[$field] ?? null;
        }

        $result = AuthService::PasswordRecovery(...$preparedData);

        if ($result["success"] === false) {
            Setup::setSession("error_message", $result["error"]);
            Setup::setSession("post", $_POST);
            AuthGetController::PasswordRecovery();
        }

        $_SESSION["success_message"] = $result["message"];
        Setup::redirect("/auth/login", 200);
    }
}
