<?php

class AuthPostController
{
    public static function Register(): void
    {
        $inputData = Setup::getJsonData();

        $result = AuthService::register(
            $inputData["email"] ?? null,
            $inputData["password"] ?? null,
            $inputData["fullname"] ?? null,
        );

        if ($result["success"] === false) {
            Response::badRequest($result["error"])->send();
        }

        Response::created($result["data"])->send();
    }
}
