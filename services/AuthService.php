<?php

class AuthService
{
    public static function register(?string $email, ?string $password, ?string $fullname): array
    {
        global $db;

        $validationResult = self::registerValidations($email, $password, $fullname);
        if ($validationResult["success"] === false) {
            return $validationResult;
        }

        $result = self::get("email", $email);
        if ($result["success"] === true) {
            return ["success" => false, "error" => LANGUAGE["email_exists"]];
        }

        try {
            $data = [
                "id" => uniqid(),
                "email" => $email,
                "password" => password_hash($password, PASSWORD_DEFAULT),
                "fullname" => $fullname,
            ];
            $db->create("users", $data);

            $result = self::get("email", $email);
            if ($result["success"] === false) {
                throw new Exception("User not found after registration.");
            }

            $db->commit();
            
            $user = $result["data"];
            unset($user["password"]);
            $cleanedUser = Validations::removeNullFields($user);

            return ["success" => true, "data" => $cleanedUser];
        } catch (Exception $e) {
            $db->rollBack();
            Response::serverError($e->getMessage(), $e->getTrace())->send();
            return [];
        }
    }

    public static function get(string $column, string $value): array
    {
        global $db;

        if (empty($column) || empty($value)) {
            return ["success" => false, "error" => "Invalid 'column' or 'value'."];
        }

        $users = $db->read("users", [$column => $value]);

        if (!empty($users) && count($users) > 0) {
            return ["success" => true, "data" => $users[0]];
        }

        return ["success" => false];
    }

    private static function registerValidations(?string $email, ?string $password, ?string $fullname): array
    {
        if (empty($email) || empty($password) || empty($fullname)) {
            return ["success" => false, "error" => LANGUAGE["all_fields_are_required"]];
        }

        if (!Validations::validateEmail($email)) {
            return ["success" => false, "error" => LANGUAGE["invalid_email"]];
        }

        $fullnameValidation = Validations::validateText(
            $fullname,
            2,
            100,
            [
                "text_is_empty" => LANGUAGE["text_is_empty"],
                "text_too_short" => LANGUAGE["text_too_short"],
                "text_too_long" => LANGUAGE["text_too_long"],
            ]
        );
        if ($fullnameValidation["success"] === false) {
            return ["success" => false, "error" => $fullnameValidation["error"]];
        }

        if (strlen($password) < 8) {
            return ["success" => false, "error" => LANGUAGE["password_too_short"]];
        }

        return ["success" => true];
    }
}