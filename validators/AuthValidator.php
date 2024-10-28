<?php

class AuthValidator
{
    public static function validateRegister(?string $email, ?string $password, ?string $first_name, ?string $last_name): array
    {
        if (empty($email) || empty($password) || empty($first_name) || empty($last_name)) {
            return ["success" => false, "error" => LANGUAGE["all_fields_are_required"]];
        }

        if (!Validations::validateEmail($email)) {
            return ["success" => false, "error" => LANGUAGE["invalid_email"]];
        }

        if (strlen($password) < 8) {
            return ["success" => false, "error" => LANGUAGE["password_too_short"]];
        }

        return ["success" => true];
    }

    public static function validateLogin(?string $email, ?string $password): array
    {
        if (empty($email) || empty($password)) {
            return ["success" => false, "error" => LANGUAGE["all_fields_are_required"]];
        }

        if (!Validations::validateEmail($email)) {
            return ["success" => false, "error" => LANGUAGE["invalid_email"]];
        }

        if (strlen($password) < 8) {
            return ["success" => false, "error" => LANGUAGE["password_too_short"]];
        }

        return ["success" => true];
    }
}
