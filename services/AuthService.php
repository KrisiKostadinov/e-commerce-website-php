<?php

class AuthService
{
    public static function register(?string $email, ?string $password, ?string $first_name, ?string $last_name, ?string $phone_number, ?string $address, ?string $city, ?string $state, ?string $country): array {
        global $db;

        $validationResult = AuthValidator::validateRegister($email, $password, $first_name, $last_name);
        if (!$validationResult["success"]) {
            return $validationResult;
        }
    
        if (self::get("email", $email)["success"]) {
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
    
            $emailResult = self::sendSuccessRegistrationEmail($first_name, $last_name, $email, $phone_number, $city, $address);
            if (!$emailResult["success"]) {
                $db->rollBack();
                return $emailResult;
            }

            $db->commit();
    
            unset($data["password"]);
            $cleanedUser = Validations::removeNullFields($data);
    
            return ["success" => true, "data" => $cleanedUser];
            
        } catch (Exception $e) {
            $db->rollBack();
            Response::serverError($e->getMessage(), $e->getTrace())->send();
            return ["success" => false, "error" => "Registration failed. Please try again later."];
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

    // mails
    private static function sendSuccessRegistrationEmail(
        string $first_name,
        string $last_name,
        string $email,
        ?string $phone_number,
        ?string $city,
        ?string $address
    ): array {
        $mailManager = new MailService(
            $email,
            SETTINGS["website_email"],
            LANGUAGE["success_registration"],
        );

        $variables = [
            "fullname" => "$first_name $last_name",
            "first_name" => $first_name,
            "last_name" => $last_name,
            "phone_number" => $phone_number,
            "city" => $city,
            "address" => $address,
            "website_display_name" => SETTINGS["website_display_name"],
            "email" => $email,
            "website_email" => SETTINGS["website_email"],
            "website_phone" => SETTINGS["website_phone"],
        ];

        $mailManager->loadTemplate("success-registration", $variables);

        $result = $mailManager->send();
        return $result;
    }
}