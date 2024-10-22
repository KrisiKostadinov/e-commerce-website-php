<?php

class AuthService
{
    public static function register(
        ?string $email,
        ?string $password,
        ?string $first_name,
        ?string $last_name,
        ?string $phone_number,
        ?string $address,
        ?string $city,
        ?string $state,
        ?string $country): array
    {
        global $db;

        $validationResult = AuthValidator::validateRegister($email, $password, $first_name, $last_name);
        if ($validationResult["success"] === false) {
            return $validationResult;
        }

        $result = self::get("email", $email);
        if ($result["success"] === true) {
            return ["success" => false, "error" => LANGUAGE["email_exists"]];
        }

        try {
            $data = [
                "email" => $email,
                "password" => password_hash($password, PASSWORD_DEFAULT),
                "first_name" => $first_name,
                "last_name" => $last_name,
                "phone_number" => $phone_number,
                "address" => $address,
                "city" => $city,
                "state" => $state,
                "country" => $country,
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
}