<?php

class Setup
{
    public static function initializeCookies(): void
    {
        if (empty($_SESSION["language"])) {
            $_SESSION["language"] = "bg";
        }

        if (empty($_SESSION["theme"])) {
            $_SESSION["theme"] = "light";
        }
    }

    public static function getJsonData(): array
    {
        $json = file_get_contents("php://input");

        $data = json_decode($json, true);

        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            return [];
        }

        return $data;
    }

    public static function View(string $templateName, array $data = array(), int $statusCode = 200): void
    {
        http_response_code($statusCode);
        extract($data);
        require "views/" . $templateName . "/page.php";
        exit;
    }

    public static function redirect(string $url, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header("Location: $url");
        exit;
    }

    public static function setSession(string $key, mixed $value): void
    {
        if (!session_id()) {
            exit("The session is not activated");
        }
        $_SESSION[$key] = $value;
    }
}
