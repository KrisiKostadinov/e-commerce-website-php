<?php

class AuthValidator
{
    public static function validateRegister(?string $email, ?string $password, ?string $cpassword): array
    {
        if ($password !== $cpassword) {
            return ["success" => false, "error" => LANGUAGE["passwords_not_match"]];
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

    public static function validatePasswordRecovery(?string $password, ?string $cpassword): array
    {
        if (empty($password) || empty($cpassword)) {
            return ["success" => false, "error" => LANGUAGE["all_fields_are_required"]];
        }

        if (strlen($password) < 8) {
            return ["success" => false, "error" => LANGUAGE["password_too_short"]];
        }

        if ($password !== $password) {
            return ["success" => false, "error" => LANGUAGE["passwords_not_match"]];
        }

        return ["success" => true];
    }
}
