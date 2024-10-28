<?php

class AuthPostController
{
    private static array $registerFields = ["email", "password", "first_name", "last_name", "phone_number", "address", "state", "city", "country"];
    private static array $loginFields = ["email", "password"];

    public static function Register(): void
    {
        $preparedData = [];

        foreach (self::$registerFields as $field) {
            $preparedData[$field] = $_POST[$field] ?? null;
        }

        $result = AuthService::register(...$preparedData);

        if ($result["success"] === false) {
            Setup::setSession("error_message", $result["error"]);
            Setup::setSession("post", $_POST);
            AuthGetController::Register();
        }

        Setup::redirect("/auth/login", 200);
    }

    public static function Login(): void
    {
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
}
